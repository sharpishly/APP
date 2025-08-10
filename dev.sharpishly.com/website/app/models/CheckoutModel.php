<?php

namespace App\Models;
use Str\Str;
use dBug\dBug;
use Exception;
use Session\Session;

class CheckoutModel extends Model {

	public $session;

    public function main($data,$models){

		$this->session = new Session();

        $options = [];

		//@TODO: Convert to loop
		$data = $this->set($data,'tbl','migrate_interviews');

		$data = $this->set($data,'tbl_users','migrate_users');

		$data = $this->set($data,'tbl_cart','migrate_products_cart');

		$data = $this->set($data,'tbl_products','migrate_products');

		$data = $this->set($data,'tbl_orders','migrate_products_orders');

		// product_order_item
		$data = $this->set($data,'tbl_order_item','migrate_product_order_item');

		//@TODO: get Id from session
		$data = $this->set($data,'user_id',$this->session->getUserId());

		$data = $this->set($data,'tbl_notes','migrate_interviews_notes');

		$data = $this->header($data);

		$data = $this->set_header_link($data,$models,$options);
			
		$data = $this->footer($data);

        $data = $this->index($data,$models,$options);

		$data = $this->update($data,$models,$options);

		$data = $this->create($data,$models,$options);

		$data = $this->modify($data,$models,$options);

		$data = $this->add($data,$models,$options);

		$data = $this->details($data,$models,$options);

		$data = $this->notes($data,$models,$options);

		$data = $this->add_note($data,$models,$options);

		$data = $this->login($data,$models,$options);

		$data = $this->gateway($data,$models,$options);

		$data = $this->checkout($data,$models,$options);

        new dBug($data);

		// new dBug($_SESSION);

        // die();

        return $data;
    }

	public function set_header_link($data,$models,$options){

		$fields = array(
			'add'=>'create',
			'login'=>'login',
			'records'=>'index'

		);

		foreach($fields as $key => $value){

			$data = $this->header_set_links($data,$key,$value,$options);

		}

		return $data;
	}

	public function get_product_by_id($data,$id){

		$wheres = array(
			'id'=>$id
		);

		$conditions = array(
			'table'=>$data['tbl_products'],
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$data = $this->set($data,__FUNCTION__,$rs['result'][0]);


		return $rs['result'][0];
	}

	public function out_of_stock_products($data,$outOfStockProducts){
        if (!empty($outOfStockProducts)) {
            $errorMessage = 'The following products are out of stock: ';
			$data = $this->set($data,__FUNCTION__,$errorMessage);
            // foreach ($outOfStockProducts as $product) {
            //     $errorMessage .= "{$product['name']} (Available: {$product['available_stock']}, Requested: {$product['requested_quantity']}), ";
            // }
            // $errorMessage = rtrim($errorMessage, ', '); // Remove the trailing comma
            // $this->flash('error', $errorMessage);
            // $this->redirect('/cart');
            return;
        }
		return $data;		
	}

	public function get_order_data($data){

		$rs = array('foo'=>'bar');

		return $rs;
	}

	public function create_order($data,$userId, $totalAmount, $orderNumber, $status){

		$save = array(
            'user_id' => $userId,
            'order_number' => $orderNumber, // Unique order identifier
            'total_amount' => $totalAmount,
            'status'=>1,
            'shipping_address_id' => 1, // Foreign key to a shipping_addresses table
            'billing_address_id' => 1,  // Foreign key to a billing_addresses table
            'payment_method' => 'paypal', // e.g., "credit_card", "paypal"
            'transaction_id' => 1, // Store transaction ID from payment gateway
            'created_at' => $this->timewarp->now(),
            'updated_at' => $this->timewarp->now(),

		);

		$conditions = array(
			'table'=>$data['tbl_orders'],
			'save'=>$save
		);

		$rs = $this->db->save($conditions);

		// new dBug($rs['inserted']);die();

		return $rs['inserted'];
	}

	// Create Order item
	public function create_order_item($data,$orderId, $product_id, $quantity, $price, $subtotal=false){

		$save = array(
            'order_id' => $orderId,
            'product_id' => $product_id,
            'quantity'=>$quantity,
            'price' => $price,
            'created_at' => $this->timewarp->now(),
		);

		$conditions = array(
			'table'=>$data['tbl_order_item'],
			'save'=>$save
		);

		$rs = $this->db->save($conditions);

		// new dBug($rs['inserted']);die();

		return $rs;		

	}

	// Update product
	public function update_stock($data,$product_id,$quantity){

		//@TODO: Add quantity field using content while testing
		$update = array(
			'stock'=>$quantity
		);

		$wheres = array(
			'id'=>$product_id
		);

		$conditions = array(
			'table'=>$data['tbl_products'],
			'update'=>$update,
			'where'=>$wheres
		);
		new dBug($conditions);
		$rs = $this->db->update($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;

	}

	// Clear cart
	public function clear_cart($data){


		$update = array(
			'status'=>2
		);

		$wheres = array(
			'user_id'=>$data['user_id']
		);

		$conditions = array(
			'table'=>$data['tbl_cart'],
			'update'=>$update,
			'where'=>$wheres
		);
		new dBug($conditions);
		$rs = $this->db->update($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

    /**
     * Handle the order processing logic.
     *
     * @return void
     */
    public function process_order($data)
    {

        $totalAmount = 0;

        $outOfStockProducts = [];

		$cartItems = $data['get_cart_by_user_id'];

        foreach ($cartItems as $item) {

			$product = $this->get_product_by_id($data,$item['id']);

			// new dBug($product);die();

			if ($product['stock'] < $item['quantity']) {
                $outOfStockProducts[] = [
                    'name' => $product['name'],
                    'available_stock' => $product['stock'],
                    'requested_quantity' => $item['quantity'],
                ];
            }

			$totalAmount += ($product['price'] * $item['quantity']); // calculate total amount

			$data = $this->out_of_stock_products($data,$outOfStockProducts);

			// 4.  Get Order Data
			$orderData = $this->get_order_data($data); // Validate and retrieve order data (shipping, billing, etc.)
			if (isset($orderData['error'])) {
			    // $this->flash('error', $orderData['message']);
			    // $this->redirect('/checkout'); //redirect to checkout
			    // return;
			}

			$orderNumber = Str::random(10); // Generate a unique order number.
			
			$status = 1;  // Initial order status

			// // 5. Begin Database Transaction
			$this->db->beginTransaction(); // Adapt to your framework's transaction handling

			$orderId = 0;

			try {

				// 6. Create Order
				$orderId = $this->create_order($data,$data['user_id'], $totalAmount, $orderNumber, $status);

				$cartItems = $data['get_cart_by_user_id'];

				// 7. Create Order Items
				foreach ($cartItems as $item) {

					$product = $this->get_product_by_id($data,$item['product_id']); //get current price.
					
					$subtotal = $product['price'] * $item['quantity'];
					
					////$this->orderItemModel->createOrderItem($orderId, $item->product_id, $item->quantity, $product->price, $subtotal);
					$order_item = $this->create_order_item($data,$orderId,$product['id'],$item['quantity'],$product['price']);

					new dBug($order_item);

					// 8. Update Product Stock
					//$this->productModel->updateStock($item->product_id, $item->quantity);
					$data = $this->update_stock($data,$product['id'],$item['quantity']);

					// clear cart
					$data = $this->clear_cart($data);
				}
				

			} catch (Exception $e){
				
			}

			$debug = array(
				'item'=>$item,
				'product'=>$product,
				'out_of_stock_products'=>$outOfStockProducts,
				'total_amount'=>$totalAmount,
				'order_number'=>$orderNumber,
				'status'=>$status,
				'subtotal'=>$subtotal,
				'data'=>$data,
				'order_id'=>$orderId
			);

			new dBug($debug);

			$this->db->commit();
			
			die();	


        }



        // try {
        //     // 6. Create Order
        //     $orderId = $this->orderModel->createOrder($userId, $totalAmount, $orderNumber, $status);

        //     // 7. Create Order Items
        //     foreach ($cartItems as $item) {
        //         $product = $this->productModel->getProductById($item->product_id); //get current price.
        //         $subtotal = $product->price * $item->quantity;
        //         $this->orderItemModel->createOrderItem($orderId, $item->product_id, $item->quantity, $product->price, $subtotal);

        //         // 8. Update Product Stock
        //         $this->productModel->updateStock($item->product_id, $item->quantity);
        //     }

        //     // 9. Commit Transaction
        //     DB::commit();

        //     // 10. Clear Cart
        //     $this->cartModel->clearCart($userId);

        //     // 11. Send Order Confirmation Email
        //     $this->sendOrderConfirmationEmail($orderId); //  Adapt this method

        //     // 12. Display Order Confirmation Page
        //     $this->view->orderNumber = $orderNumber;
        //     $this->view->orderId = $orderId;
        //     $this->view->totalAmount = $totalAmount;
        //     $this->view->render('checkout/confirmation'); // Create this view
        // } catch (Exception $e) {
        //     // 13. Rollback Transaction and Handle Error
        //     DB::rollBack();
        //     $this->handleError("Error processing order: " . $e->getMessage()); // Adapt error handling
        //     $this->flash('error', 'There was a problem processing your order. Please try again.');
        //     $this->redirect('/checkout'); //redirect
        //     return;
        // }

		return $data;
    }


	public function checkout($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->get_cart_by_user_id($data);

			if(isset($data['get_cart_by_user_id'][0])){

				$data = $this->get_user_data($data);
	
				$data = $this->get_shipping_options($data);
	
				$data = $this->process_order($data);

			}

			$msg = 'foo';

			$title = 'bar';

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', $msg);
			
		}
		
		return $data;
		
	}

	public function get_cart_by_user_id($data){

        // $userId = auth()->user()->id; // Get the logged-in user's ID

		$wheres = array(
			'user_id'=>$data['user_id'],
			'status'=>1
		);

		$conditions = array(
			'table'=>$data['tbl_cart'],
			'wheres'=>$wheres
		);

		$cart = $this->db->find($conditions);

		$data = $this->set($data,__FUNCTION__,$cart['result']);

		if(!isset($cart['result'][0]['id'])){

			// $this->redirect('/cart'); // Redirect to cart page if it is empty
			// return;
		}
		
		return $data;
	}

	// Get user data, shipping options, etc.
    // $userData = $this->getUserData($userId);  //Adapt this method
	public function get_user_data($data){

		$wheres = array(
			'id'=>$data['user_id']
		);

		$conditions = array(
			'table'=>$data['tbl_users'],
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

	public function get_shipping_options($data){

		$rs = array('address'=>array('foo'=>'bar'));

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

	public function gateway($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$msg = 'foo';

			$title = 'bar';

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', $msg);
			
		}
		
		return $data;
		
	}

	public function login($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = 'Login';

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Please ' . $title);
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->login_with_google($data,$models,$options);

			$data = $this->header_set_links($data,'funding-records','records');

			$options['url'] = 'gateway';

			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Email'=>array(
					'name'=>'email',
					'placeholder'=>'Please enter your email',
					'type'=>'text',
					'required'=>'required'
				),
				'Password'=>array(
					'name'=>'password',
					'placeholder'=>'Provide your email',
					'type'=>'password',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			
		}
		
		return $data;
		
	}

	public function login_with_google($data,$models,$options){

        $url =  'google/auth';

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>'link',
		);
        
        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data, __FUNCTION__, $link);

		return $data;
	}

	public function add_note($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['tbl'] = $data['tbl_notes'];
			
			$data = $this->save($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title','::Hub::');

			$data = $this->partials->template($data,'home',$this->helper->url($data['model'] . "/details/" . $data['id']));
			
		}
		
		return $data;
		
	}

	public function notes($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['url'] = 'add_note/' . $data['id'];
			
			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Title'=>array(
					'name'=>'title',
					'placeholder'=>'What is the name?',
					'type'=>'text',
					'required'=>'required'
				),
				'Description'=>array(
					'name'=>'description',
					'placeholder'=>'Provide a description?',
					'type'=>'text',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'note_id',$data['id']);

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}

	public function details($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->get_note_by_id($data,$models,$options);

			$wheres = array(
				'id'=>$data['id']
			);
			
			$conditions = array(
				'table'=>$data['tbl'],
				'order'=>array('id'=>'DESC'),
				'wheres'=>$wheres
			);

			$rs = $this->db->find($conditions);

			$rs = $this->check_if_records_exists($rs,$options);

			$rs = $this->set_record_url($rs,'link','update',$data);
				
			$data = $this->partials->spartials($data,'records',$rs['result']);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update ' . ucfirst($data['title']));

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}


	public function add($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){
			
			$save = $this->status($_POST);

			$save = $this->puritycontrol->safe_strings_for_db($save);

			$conditions = array(
				'table'=>$data['tbl'],
				'save'=>$save
			);
	
			$rs = $this->db->save($conditions);

			$data = $this->set($data,'save',$rs);

			$data = $this->set($data,'conditions',$conditions);

			$title = "Add funding source";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');

			$url = $this->helper->url($data['model'] . "/index");

			$attr = array(
				'href'=>$url,
				'id'=>'link'
			);

			$link = $this->attributes->set($attr);
                        
            $data = $this->partials->template($data, 'url', $link);
			
		}
		
		return $data;
		
	}

	public function modify($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['tbl'] = $data['tbl'];
			
			$data = $this->updates($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of todo');

			$data = $this->partials->template($data,'title','::Hub::');

			$url =$this->helper->url($data['model'] . "/details/" . $_POST['id']);

			$data = $this->partials->template($data,'home',$url);
			
		}
		
		return $data;
		
	}

	public function create($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = "Add funding source";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->header_set_links($data,'funding-records','records');

			$options['url'] = 'add';

			$data = $this->form->set($data,$models,$options);

			$options['migration_to_form'] = $data['tbl'];

			$data = $this->migration_to_form($data,$models,$options);			
		}
		
		return $data;
		
	}

	public function status_to_partials($data,$models=false,$options=false){

		$priority = $this->form->statusToPartials($data);

		$priority = $this->set_selected_status($priority,$data);

		$data = $this->partials->spartials($data,'selector',$priority);

		return $data;
	}

	public function set_selected_status($priority,$data){

		$id = $data['database_records']['result'][0]['status'];

		foreach($priority as $key => $value){

			$arr = array(
				'value'=>$value['val']
			);

			if($id == $value['val']){

				$arr['selected'] = 'selected';

			}

			$attr = $this->attributes->set($arr);

			$value['attr'] = $attr;

			// new dBug($value);

			$priority[$key] = $value;
		}

		// new dBug($priority);

		return $priority;
	}

	public function update($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['restrict'] = array('content','date');

			$data = $this->get_record_by_id($data,$models,$options);

			$data = $this->create_form_from_partial($data,$models,$options);

			$data = $this->status_to_partials($data);

			//@TODO temp unset
			unset($data['partials']['fields_all'][7]);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update ' . $data['model']);

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');

			$options['url'] = 'modify';

			$data = $this->form->set($data,$models,$options);

			$data = $this->partials->template($data,'add',$this->helper->url($data['model'] . "/notes/" . $data['id']));

			$data = $this->partials->template($data,'details',$this->helper->url($data['model'] . "/details/" . $data['id']));
		}
		
		return $data;
		
	}

	public function get_record_by_id($data,$models,$options){

		$wheres = array(
			'id'=>$data['id']
		);
		
		$conditions = array(
			'table'=>$data['tbl'],
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		$data = $this->set($data,'database_records',$rs);

		$rs = $this->set_record_url($rs,'foo','bar',$data);

		$data = $this->partials->spartials($data,'records',$rs['result']);

		return $data;
	}

	public function get_note_by_id($data,$models,$options){

		$wheres = array(
			'noteid'=>$data['id']
		);
		
		$conditions = array(
			'table'=>$data['tbl_notes'],
			//'order'=>array('id'=>'DESC'),
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		//$rs = $this->decodeHtmlEntities($rs,$data);

		$data = $this->partials->spartials($data,'notes',$rs['result']);

		return $data;
	}


    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->pagination($data);

			$data = $this->filter_records_by_id($data,$models,$options);
			
			$wheres = array(
				'status'=>'1',
				'or'=>array(
					'status'=>array(2,3,5)
				)
			);

			$conditions = array(
				'table'=>$data['tbl'],
				'order'=>array('id'=>$data['sort']),
				'limit'=>$data['pagination'],
				'wheres'=>$wheres
			);
			// new dBug($conditions);die();
			$rs = $this->db->find($conditions);

			$rs = $this->check_if_records_exists($rs,$options);

			$rs = $this->set_record_url($rs,'link','update',$data);

			$rs = $this->check_if_domain_exists($rs,$options);

			$rs = $this->set_external_url($rs,'external');

			if(!isset($rs['result'][0]['id'])){

				$arr = array(
					array(
						'id'=>'',
						'name'=>'',
						'date'=>''
					)
				);

				$rs['result'] = $arr;

			}

			$data = $this->partials->spartials($data,'records',$rs['result']);
			
			$data = $this->set($data, 'records', $rs);
			
			$data = $this->set($data, 'title', $data['title']);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update ' . ucfirst($data['model']));

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');

			
		}
		
		return $data;
		
	}

	public function set_external_url($rs,$partial){

		foreach($rs['result'] as $key => $value){


			$attr = array(
				'href'=>$value['url'],
				'class'=>'_link',
				'target'=>'_blank'
			);

			$link = $this->attributes->set($attr);

			$value[$partial] = $link;

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function set_record_url($rs,$partial,$name,$data){

		foreach($rs['result'] as $key => $value){

			$url = $this->helper->url($data['model'] . '/' . $name . '/' . $value['id']);

			$attr = array(
				'href'=>$url,
				'class'=>'_link'
			);

			$link = $this->attributes->set($attr);

			$value[$partial] = $link;

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

}

?>