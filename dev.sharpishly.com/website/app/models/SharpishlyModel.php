<?php

namespace App\Models;
use Session\Session;
use dBug\dBug;
use Flash\Flash;
use CurlRequest\CurlRequest;

class SharpishlyModel extends Model {

	public $session;

	public $flash;

	public $request;

    public function main($data,$models){

		//@TODO: Reduce main menu for non-members

		//@TODO: Back to shop on cart page

		//@TODO: Hide form on shop page
		
		$this->session  = new Session();

		$this->flash = new Flash($this->partials,$this->attributes,$this->helper);

		$this->request = new CurlRequest();

        $options = [];

		$data = $this->set($data,'tbl','migrate_interviews');

		$data = $this->set($data,'tbl_notes','migrate_interviews_notes');

		$data = $this->set($data,'tbl_businesses','migrate_businesses');

		$data = $this->set($data,'tbl_products','migrate_products');

		$data = $this->set($data,'tbl_products_cart','migrate_products_cart');

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

		$data = $this->home($data,$models,$options);

		$data = $this->portal($data,$models,$options);

		$data = $this->tradeonly($data,$models,$options);

		$data = $this->taxrebate($data,$models,$options);

		$data = $this->businessinabox($data,$models,$options);

		$data = $this->debtmanagement($data,$models,$options);

		$data = $this->customercare($data,$models,$options);

		$data = $this->collectivebargaining($data,$models,$options);

		$data = $this->shop($data,$models,$options);

		$data = $this->cart($data,$models,$options);

		$data = $this->registration($data,$models,$options);

		$data = $this->register($data,$models,$options);

		$data = $this->terms($data,$models,$options);

		$data = $this->policy($data,$models,$options);

		$data = $this->basket($data,$models,$options);

		$data = $this->checkout($data,$models,$options);

		$data = $this->updatecart($data,$models,$options);

		$data = $this->updatecartitem($data,$models,$options);

		$data = $this->membership($data,$models,$options);

		$data = $this->buy($data,$models,$options);

		$data = $this->start($data,$models,$options);

		$data = $this->seed($data,$models,$options);

		// $data = $this->set($data,'COOKIE',$_COOKIE);

		// $data = $this->set($data,'SESSION',$_SESSION);

		// new dBug($data);

        return $data;
    }



	public function save_new_seed($data,$title,$decription){

		$date = $this->timewarp->now();

		$save = array(
			'title'=>$title,
			'description'=>$decription,
			'date'=>$date,
			'status'=>1
		);

		$conditions = array(
			'table'=>'migrate_seeds',
			'save'=>$save

		);

		$rs = $this->db->save($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

	public function seeding($data,$title,$decription,$seed){

		$debug = array(
			'data'=>$data,
			'title'=>$title,
			'description'=>$decription,
			'seed'=>$seed
		);

		// new dBug($debug);

		$wheres = array(
			'title'=>$title,
			'description'=>$decription
		);

		$conditions = array(
			'table'=>'migrate_seeds',
			'wheres'=>$wheres

		);

		$rs = $this->db->find($conditions);

		if(!isset($rs['result'][0]['id'])){

			// Do save new seed
			$data = $this->save_new_seed($data,$title,$decription);

			$data = $this->set($data,__FUNCTION__,$this->db->savem($seed));

		} else {

			// Stop seeding of new records
			new dBug($data[__FUNCTION__] = array('error'=>'data already seeded'));
		}

		return $data;
	}

	public function seed($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$conditions = array();

			$date = $this->timewarp->now();

			$fields = array(
				'Chorlton Cobblers'=>'Tea &amp; Sticky shoes',
				'Ye Olde Cake Shoppe'=>'Tea &amp; Sticky Cakes',
				'Ye Olde Computer Repair'=>'New fanglled technology',
				'Exotic Flowers shoppe'=>'Amazing flowers from around the world',
				'Lovely Coffee'=>'Tea &amp; Sticky coffee',
				'Penny Farthing Bicycles'=>'Back in the saddle',
				'Ye Olde Computer Repair'=>'Wiring machines',
				'Exotic Flowers shoppe'=>'Amazing flowers from around the world'
			);

			foreach ($fields as $title => $description) {

				$save = array(
					'title' => $title,
					'description' => $description,
					'date'=>$date,
					'status'=>1
				);

				$conditions[] = array(
					'table' => $data['tbl_businesses'],
					'save' => $save,
				);

			}

			$data = $this->seeding($data,'chorlton_shops_test','foo',$conditions);

			$data = $this->business($data);

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => 'Welcome to your community website',
				'h2' => 'Click on the link below',
				'title' => $title,
				'chorlton_link'=>$this->quick_link('sharpishly/home/1'),
				'chorlton_title'=>'Chorlton-Cum-Hardy Community Website'
			);

			$data = $this->partials->templates($data,$arr);

		}
		
		return $data;
		
	}

	public function start($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->business($data);

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => 'Welcome to your community website',
				'h2' => 'Click on the link below',
				'title' => $title,
				'chorlton_link'=>$this->quick_link('sharpishly/home/1'),
				'chorlton_title'=>'Chorlton-Cum-Hardy Community Website'
			);

			$data = $this->partials->templates($data,$arr);

		}
		
		return $data;
		
	}

		public function buy($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->paymentlinks($data,$models,$options);

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => $title . ': &pound;' . ucfirst($data['id']),
				'h2' => 'Please upgrade your membership. Follow the details below',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);

		}
		
		return $data;
		
	}

		public function membership($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => $title . ':' . ucfirst($data['id']),
				'h2' => 'Please upgrade your membership. Follow the details below',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);

		}
		
		return $data;
		
	}

	public function hidden($data,$options){

		if(isset($data['get_cart_item_by_id']['id'])){

			$access = array(
				//'product_id',
				//'user_id',
				'id'
			);

			$part = array();

			foreach($data['get_cart_item_by_id'] as $key => $value){
				
				if(in_array($key,$access)){

					$attr = array(
						'name'=>$key,
						'id'=>$key,
						'value'=>$value,
						'type'=>'hidden'
					);

					$part[]['hidden'] = $this->attributes->set($attr);
				}
			}

			// $key = 'updated_at';

			// $value = $this->timewarp->now();
			
			// $attr = array(
			// 	'name'=>$key,
			// 	'id'=>$key,
			// 	'value'=>$value,
			// 	'type'=>'hidden'
			// );

			// $part[]['hidden'] = $this->attributes->set($attr);

			$data = $this->partials->spartials($data,__FUNCTION__,$part);

		}

		return $data;
	}

	public function precart($data,$options){

		if(isset($data['get_cart_item_by_id']['id'])){

			$access = array(
				'quantity',
				//'status'
			);

			$part = array();

			foreach($data['get_cart_item_by_id'] as $key => $value){
				
				if(in_array($key,$access)){

					$attr = array(
						'name'=>$key,
						'id'=>$key
					);

					$select = $this->attributes->set($attr);

					$opts = $this->set_opts();

					$part[] = array(
						'title'=>ucfirst($key),
						'description'=>$value,
						'select'=>$this->form->select($select,$opts)
					);
				}
			}

			$opts = $this->set_opts_key_value();

			$key = 'status';

			$attr = array(
				'name'=>$key,
				'id'=>$key
			);

			$select = $this->attributes->set($attr);			

			$part[] = array(
				'title'=>'remove',
				'description'=>'remove',
				'select'=>$this->form->select($select,$opts)
			);			

			$data = $this->partials->spartials($data,__FUNCTION__,$part);

		}

		return $data;
	}

	public function set_opts(){

		$max = 10;

		$count = 1;

		$rs = array();

		while($count<$max){

			$rs[$count] = "value='$count'";

			$count++;

		}

		return $rs;

	}

	public function set_opts_key_value(){


		$rs = array();

		$fields = array(
			'keep'=>1,
			'remove'=>2
		);

		foreach($fields as $key => $value){

			$rs[$key] = "value='$value'";

		}		

		return $rs;

	}

	public function updatecartitem($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$update = $_POST;

			unset($update['id']);

			$conditions = array(
				'table'=>$data['tbl_products_cart'],
				'update'=>$update,
				'where'=>array('id'=>$_POST['id'])
			);
			// new dBug($conditions);//die();
			$rs = $this->db->update($conditions);
			// new dBug($rs);
			$data =  $this->set($data,__FUNCTION__,$rs);

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => 'Successful',
				'h2' => 'Update or remove item',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function updatecart($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->get_cart_item_by_id($data,$options);

			$data = $this->precart($data,$options);

			$data = $this->hidden($data,$options);

			$title = ucfirst(__FUNCTION__);

			//@TODO: Add condition check if this field
			$h1 = $data['get_cart_item_by_id']['biz_name'];

			$arr = array(
				'h1' => $h1,
				'h2' => 'Update or remove item',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);

			$options['url'] = 'updatecartitem';

			$data = $this->form->set($data,$models,$options);
			
		}
		
		return $data;
		
	}

	public function get_cart_item_by_id($data,$options){

		$wheres = array(
			'id'=>$data['id']
		);

		$conditions = array(
			'table'=>$data['tbl_products_cart'],
			'wheres'=>$wheres
		);

		$conditions = $this->recordjoins($data,$conditions,$options);

		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0]['id'])){

			$result = $rs['result'][0];

		} else {

			$result = false;

		}

		$data = $this->set($data,__FUNCTION__,$result);

		return $data;
	}

	public function terms($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => 'Terms and Conditions',
				'h2' => '1. Acceptance of Terms',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);
	
		}
		
		return $data;
		
	}

	public function policy($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => 'Policy Page',
				'h2' => '1. Introduction',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);
	
		}
		
		return $data;
		
	}


	public function set_flash($data, $models=false, $options=false){

		if(isset($data['redirect']['error'])){

			$arr = array(
				'class'=>'layout-item',
			);

			// new dBug($data['redirect']);

		} else {

			$arr = array(
				'class'=>'layout-item',
				'style'=>'display:none'
			);

		}

		$data = $this->set_flash_attributes($data,$arr);

		return $data;
	}

	public function set_flash_attributes($data,$arr){

        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data,'flash', $link);

		return $data;
	}

	public function register($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$save = $_POST;
			$save['status'] = 1;
			$save['date'] = date('Y-m-d h:m:s');
			$save['pref'] = 'registration';

			$tbl = 'migrate_users';

			$conditions = array(
				'table' => $tbl,
				'save'=>$save
			);

			$rs = $this->db->save($conditions);

			$data = $this->set($data,'save_user',$rs);

			if(isset($rs['inserted'])){

				$id = $rs['inserted'];

				$wheres = array(
					'id' => $id
				);

				$conditions = array(
					'table' => $tbl,
					'wheres'=>$wheres
				);

				$user = $this->db->find($conditions);

				$uid = $user['result'][0];

				$this->session->setRegistrationUser($uid);

				$arr = array(
					'h1'=>$uid['first_name'] . ' you are now registered',
					'h2'=>'Please enter your details below',
					'title'=>$data['title']
				);

			} else {

				$arr = array(
					'h1'=>'Sorry Not Registred',
					'h2'=>'Please try again',
					'title'=>$data['title']
				);
			}

			$data = $this->partials->templates($data,$arr);

			$data = $this->login_page_link($data,$models,$options);
		}
		
		return $data;
		
	}

	public function login_page_link($data,$models,$options){

        $url =  'sharpishly/login';

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>'link',
		);
        
        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data, __FUNCTION__, $link);

		return $data;
	}

	public function registration($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->set_flash($data);

			$options['url'] = 'register';

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
					'placeholder'=>'Provide your password',
					'type'=>'password',
					'required'=>'required'
				),
				'Firstname'=>array(
					'name'=>'first_name',
					'placeholder'=>'Provide your first_name',
					'type'=>'first_name',
					'required'=>'required'
				),
				'Lastname'=>array(
					'name'=>'last_name',
					'placeholder'=>'Provide your last_name',
					'type'=>'last_name',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			$arr = array(
				'h1'=>'Registration Form',
				'h2'=>'Please enter your details below',
				'title'=>$data['title']
			);

			$data = $this->partials->templates($data,$arr);
		}
		
		return $data;
		
	}

	public function shoppingcart($data,$models=false,$options=false){

		$wheres = array(
			'user_id'=>$this->session->getUserId(),
			'status'=>1
		);

        $conditions = array(
            'table'=>$data['tbl_products_cart'],
            'wheres'=>$wheres,
            'calculated_columns' => [
                'total' => [
                    'price_col' => 'price', // Replace 'price' with your actual price column name
                    'quantity_col' => 'quantity' // Replace 'quantity' with your actual quantity column name
                ]
            ]
        );

		$conditions = $this->recordjoins($data,$conditions,$options);

		$rs = $this->db->find($conditions);

		$rs = $this->shoppingcart_process($rs,$data);
		
		$data = $this->partials->spartials($data,__FUNCTION__,$rs['result']);

		return $data;
	}

	public function shoppingcart_process($rs,$data){

		if(isset($rs['result'][0]['id'])){

			//new dBug($rs['result']);

			foreach($rs['result'] as $key => $value){

				//new dBug($value);

				$url = $this->helper->url('sharpishly/updatecart/' . $value['id']);

				$attr = array(
					'href'=>$url,
					'class'=>__FUNCTION__
				);

				$update = $this->attributes->set($attr);

				$value['update'] = $update;

				$rs['result'][$key] = $value;

			}

		}

		return $rs;
	}

	public function cart_save($data,$models=false,$options=false){

		$save = $_POST;

		$save['status'] = 1;

		$save['pref'] = 'active';

		$save['date'] = $this->timewarp->now();

		$conditions = array(
			'table'=>$data['tbl_products_cart'],
			'save'=>$save
		);

		// new dBug($conditions);die();

		$rs = $this->db->save($conditions);

		$data = $this->set($data,__FUNCTION__,$rs['result']);

		return $data;

	}

	public function cart($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->cart_save($data);

			$arr = array(
				'h1'=>'Cart',
				'h2'=>'Check whats in the basket',
				'title'=>$data['title']
			);

			$data = $this->partials->templates($data,$arr);

			$data = $this->shoppingcart($data);

			$data = $this->set_link($data,'sharpishly/checkout','checkout');

		}
		
		return $data;
		
	}

	public function checkout($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->baskets($data,$models,$options);

			$url = $this->helper->url('sharpishly/buy/' . $data['grandtotal']);

			$attr = array(
				'href'=>$url,
				'id'=>__FUNCTION__,
				'class'=>__FUNCTION__
			);

			$arr = array(
				'h1'=>'Click here to buy: &pound' . $data['grandtotal'],
				'h2'=>'What is in your Checkout?',
				'title'=>$data['title'],
				'buy'=>$this->attributes->set($attr)
			);

			$data = $this->partials->templates($data,$arr);

			//@TODO: payment process links
			$data = $this->checkoutlinks($data,$models,$options);

		}
		
		return $data;
		
	}

	public function checkoutlinks($data,$models,$options){

		$fields = array(
			'Shipping Address'=>'shipping',
			'Billing details'=>'billing',
			'Payment Selection'=>'payment',
			'Order Review & Final Confirmation' => 'review',
			'Order Submission & Payment Processing' => 'order',
			'Order Confirmation Email (and potentially SMS)' => 'confirmation',
			'Inventory Management & Fulfillment Handoff' => 'inventory'
		);

		$partial = array();

		foreach($fields as $key => $val){

			$link = 'sharpishly/membership/';

			$url = $this->helper->url( $link . $val);

			$attr = array(
				'href'=>$url,
				'class'=>__FUNCTION__,
				'id'=>__FUNCTION__ . '_' . $val
			);

			$part = array(
				'title'=>$key,
				'description'=>$val,
				'url'=>$this->attributes->set($attr),
				'active'=>'x'
			);

			$partial[] = $part;

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$partial);

		return $data;
	}


	public function paymentlinks($data,$models,$options){

		$fields = array(
			'Credit Card'=>'creditcard',
			'Pay Pal'=>'paypal',
		);

		$partial = array();

		foreach($fields as $key => $val){

			$link = 'sharpishly/payment/';

			$url = $this->helper->url( $link . $val);

			$attr = array(
				'href'=>$url,
				'class'=>__FUNCTION__,
				'id'=>__FUNCTION__ . '_' . $val
			);

			$part = array(
				'title'=>$key,
				'description'=>$val,
				'url'=>$this->attributes->set($attr),
				'active'=>'x'
			);

			$partial[] = $part;

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$partial);

		return $data;
	}


	public function basket($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->baskets($data,$models,$options);

			$arr = array(
				'h1'=>'Shopping Basket',
				'h2'=>'What is in your basket?',
				'title'=>$data['title']
			);

			$data = $this->set_link($data,'sharpishly/checkout','checkout');

			$data = $this->set_link($data,'sharpishly/shop','shop');

			$data = $this->partials->templates($data,$arr);

		}
		
		return $data;
		
	}

	public function baskets($data,$models,$options){

		//@TODO: Create condition function
		$wheres = array(
			'user_id'=>$this->session->getUserId(),
			'status'=>1
		);

		//@TODO: The new Db class has effected previous functionality
		$conditions = array(
			'table'=>$data['tbl_products_cart'],
			'wheres'=>$wheres,
			'calculated_columns' => [
                'total' => [
                    'price_col' => 'price', // Replace 'price' with your actual price column name
                    'quantity_col' => 'quantity' // Replace 'quantity' with your actual quantity column name
                ]
			],
            // 'grand_total' => [
            //     'name' => 'cart_grand_total', // This will be the alias for the grand total column in your result
            //     'price_col' => 'price',       // Same price column
            //     'quantity_col' => 'quantity'  // Same quantity column
			// ],
			'order'=>array('id'=>'DESC'),
			// --- THIS IS THE MISSING PART FOR grand_total ---
			'find_grand_total' => [
				'name' => 'cart_grand_total',
				'price_col' => 'price',
				'quantity_col' => 'quantity'
			]
		);

		$conditions = $this->recordjoins($data,$conditions,$options);

		$rs = $this->db->find($conditions);

		// print_r($rs);die();

		$data = $this->set($data,__FUNCTION__,$rs);

        $grandTotal = null;
        if (!empty($rs['result'])) {
            $grandTotal = $rs['result'][0]['cart_grand_total'] ?? null;
			$data = $this->set($data,'grandtotal',$grandTotal);
        }

		$rs = $this->check_if_records_exists($rs,$options);

		$rs = $this->shoppingcart_process($rs,$data);

		$data = $this->partials->spartials($data,__FUNCTION__,$rs['result']);



		return $data;
	}

	public function recordjoins($data,$conditions,$options){

		$conditions['joins']=array(						
			array(
				'type'=>'INNER',
				'on'=>array(
					'tbl1'=>'product_id',
					'tbl2'=>'id'
				),
				'table'=>$data['tbl_products'],
				'fields'=>array(
					'id'=>'biz_id',
					// 'userid'=>'biz_userid',
					'name'=>'biz_name',
					// 'description'=>'biz_description',
					// 'status'=>'biz_status'
				)
			),							
		);

		return $conditions;
		
	}

	public function shop($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['migration_to_form'] = $data['tbl_products_cart'];

			$data = $this->migration_to_form($data,$models,$options);

			// Test
			// $msg = array('title'=>'hello');

			// new dBug($data = $this->flash->start($data,$msg,'foo','hello/world'));

			$title =ucfirst(__FUNCTION__);

			$data = $this->set_flash($data);

			$arr = array(
				'h1'=>$title,
				'h2'=>'Browse products',
				'title'=>$data['title']
			);

			$data = $this->partials->templates($data,$arr);

			$data = $this->products($data);
		}
		
		return $data;
		
	}

	public function product_form($value,$data){

		$url =  $this->helper->url($data['model'] . '/cart');

		$arr = array(
			'action'=>$url,
			'method'=>'POST',
		);
		
		$value['form'] = $this->attributes->set($arr);

		return $value;
	}

	public function product_field($value,$new,$title){

		$arr = array(
			'name'=>$title,
			'value'=>$new,
			'type'=>'hidden'
		);
		
		$value[$title] = $this->attributes->set($arr);

		return $value;
	}

	public function product_attribute($name){

		$item = explode('_',$name);

		return $item[1];
	}

	public function product($products,$data){

		foreach($products as $key => $value){

			$value = $this->product_form($value,$data);

			//@TODO: Create loop
			$value['rrp'] = $value['price']; 

			$new = $value['id'];

			$value = $this->product_field($value,$new,'product_id');

			$new = $this->session->getUserId();

			$value = $this->product_field($value,$new,'user_id');

			$new = '2.23';

			$value = $this->product_field($value,$new,'price');

			$new = 5;

			$value = $this->product_field($value,$new,'quantity');

			$new = $this->timewarp->now();

			$value = $this->product_field($value,$new,'created_at');


			// new dBug($value);//die();

			$products[$key] = $value;

		}

		return $products;
	}

	public function products($data,$models=false,$options=false){

		$wheres = array(

		);

		$conditions = array(
			'table'=>$data['tbl_products'],
			'order'=>array('id'=>'DESC')
		);

		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0]['id'])){

			$products = $this->product($rs['result'],$data);

		} else {

			// Do something
			$products = array(
				'title'=>'',
				'descrtiption'=>'',
				'price'=>''
			);

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$products);

		return $data;
	}

	public function collectivebargaining($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function customercare($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function debtmanagement($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function businessinabox($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function taxrebate($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function tradeonly($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function portal($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Chorlton Community ' . ucfirst(__FUNCTION__),
				'h2'=>'Bringing local businesses to the community',
				'home'=>$this->helper->url($data['model'] . "/details/" . $data['id'])
			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}

	public function home($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$arr = array(
				'h1'=>'Home',
				'h2'=>'Welcome to Your Town&#8217;s Online Hub!',
				'businesses'=>$this->helper->url($data['model'] . "/index/" . $data['id'])

			);

			$data = $this->partials->templates($data,$arr);
			
		}
		
		return $data;
		
	}
	

	public function set_header_link($data,$models,$options){

		$fields = array(
			'add'=>'create',
			'login'=>'login',
			'records'=>'index',
			'terms'=>'terms',
			'policy'=>'policy',
			'shop'=>'shop',
			'basket'=>'basket'

		);

		foreach($fields as $key => $value){

			$data = $this->header_set_links($data,$key,$value,$options);

		}

		return $data;
	}

	public function gateway($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			// new dBug($_SESSION);

			$first_name = $this->session->getRegistration('first_name');

			$arr = array(
				'h1'=>'Home',
				'h2'=>'Welcome ' . $first_name . ' to Your Town&#8217;s Online Hub!',
				'businesses'=>$this->helper->url($data['model'] . "/index/" . $data['id'])

			);

			$data = $this->partials->templates($data,$arr);
			
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

			$data = $this->login_with_facebook($data,$models,$options);

			$data = $this->register_user($data,$models,$options);

			$data = $this->header_set_links($data,'funding-records','records');

			$options['url'] = 'gateway';

			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Email'=>array(
					'name'=>'email',
					'placeholder'=>'Please enter your email',
					'type'=>'text',
					'required'=>'required',
					'id'=>'email-field'
				),
				'Password'=>array(
					'name'=>'password',
					'placeholder'=>'Provide your password',
					'type'=>'password',
					'required'=>'required',
					'id'=>'password-field'
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

	public function register_user($data,$models,$options){

        $url =  'sharpishly/registration';

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>'link',
		);
        
        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data, __FUNCTION__, $link);

		return $data;
	}

	public function login_with_facebook($data,$models,$options){

        $url =  'facebook/auth';

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

	public function businesshours($data,$models=false,$options=false){

		$arr = array(
			array(
				'day'=>'Monday-Friday',
				'hours'=>'9:00 AM - 5:00 PM'
			),
			array(
				'day'=>'Saturday',
				'hours'=>'8:00 AM - 4:00 PM'
			),
			array(
				'day'=>'Sunday',
				'hours'=>'9:00 AM - 4:00 PM'
			)
		);

		$data = $this->partials->spartials($data,__FUNCTION__,$arr);

		return $data;
	}

	public function events($data,$models=false,$options=false){

		$arr = array(
			array(
				'class'=>'discount',
				'h3'=>'Discount Offer',
				'content'=>'Show your [Your Business Card/App Name] to receive 10% off your total purchase!'
			),array(
				'class'=>'event',
				'h3'=>'Upcoming Event',
				'content'=>'Join us for our live music night on Friday at 8PM! Special discounts available for [Your Business Card/App Name] holders.'
			),array(
				'class'=>'event',
				'h3'=>'Salsa Night',
				'content'=>'Join us for our live music night on Friday at 8PM! Special discounts available for [Your Business Card/App Name] holders.'
			),

		);

		$data = $this->partials->spartials($data,__FUNCTION__,$arr);

		return $data;
	}

	public function get_business_name($data,$models=false,$options=false){

		if(isset($data['partials']['business_all'][0]['title'])){

			$name = $data['partials']['business_all'][0]['title'];

		} else {

			$name = 'Not found';

		}

		$data = $this->set($data,__FUNCTION__,$name);

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

			$data = $this->get_business_by_id($data);

			$data = $this->businesshours($data);

			$data = $this->events($data);

			$data = $this->get_business_name($data);

			$data = $this->set($data, 'title', $data['title']);

			$arr = array(
				'h1'=>$data['get_business_name'],
				'h2'=>'Business Details Here!',
				'title'=>$data['get_business_name']
			);

			$data = $this->partials->templates($data,$arr);

			$data = $this->socials($data);

		}
		
		return $data;
		
	}


	public function socials($data,$models=false,$options=false){

		$arr = array(
			array(
				'title'=>'Facebook',
			),
			array(
				'title'=>'X/Twitter',
			),
			array(
				'title'=>'Instagram',
			),
			array(
				'title'=>'Youtube',
			),
			array(
				'title'=>'TikTok',
			)
		);

		$data = $this->partials->spartials($data,__FUNCTION__,$arr);

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
				),
				'Url'=>array(
					'name'=>'url',
					'placeholder'=>'Provide a url?',
					'type'=>'text',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			
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

	public function get_business_by_id($data,$models=false,$options=false){

		$wheres = array(
			'status'=>'1',
			'id'=>$data['id']
		);

		$conditions = array(
			'table'=>$data['tbl_businesses'],
			'order'=>array('id'=>'DESC'),
			'limit'=>5,
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		$data = $this->partials->spartials($data,'business',$rs['result']);

		return $data;
	}

	public function business($data,$models=false,$options=false){

		$wheres = array(
			'status'=>'1'
		);

		$conditions = array(
			'table'=>$data['tbl_businesses'],
			'order'=>array('id'=>'DESC'),
			'limit'=>5,
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		$rs = $this->set_record_url($rs,'link','details',$data);

		$rs = $this->set_record_url($rs,'shop','shop',$data);

		$rs = $this->set_record_url($rs,'customercare','customercare',$data);

		// $arr = array(
		// 	array(
		// 		'title'=>'Boohee',
		// 	),
		// 	array(
		// 		'title'=>'Double Zero',
		// 	),
		// 	array(
		// 		'title'=>'Ken Fosters Cycles',
		// 	),
		// 	array(
		// 		'title'=>'Yaki Soba',
		// 	),
		// 	array(
		// 		'title'=>'Dept',
		// 	),
		// 	array(
		// 		'title'=>'Auto Shield',
		// 	),
		// 	array(
		// 		'title'=>'Carringtons',
		// 	),
		// 	array(
		// 		'title'=>'The Cleaver',
		// 	),
		// 	array(
		// 		'title'=>'Corriander',
		// 	),
		// 	array(
		// 		'title'=>'Flex',
		// 	),
		// 	array(
		// 		'title'=>'South Manchester Central Heating',
		// 	),
		// 	array(
		// 		'title'=>'Fat Pats',
		// 	)
		// );

		$data = $this->partials->spartials($data,__FUNCTION__,$rs['result']);

		return $data;
	}


    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){
			
			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1', ucfirst($data['model']));

			$data = $this->partials->template($data,'h2','Chorlton Business Directory');

			$data = $this->partials->template($data,'title',ucfirst($data['model']));

			$data = $this->business($data);

			$coffee = $this->helper->image('sharpishly/coffee_shop.jpeg');

			$data = $this->partials->template($data,'coffee',$coffee);
			
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