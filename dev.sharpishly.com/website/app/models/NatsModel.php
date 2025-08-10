<?php

namespace App\Models;

use App\Controllers\Nats as ControllersNats;
use Nats\Nats;
use dBug\dBug;

class NatsModel extends Model {

    public function main($data,$models){

        $options = [];

		$data = $this->set($data,'tbl','migrate_interviews');

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

	public function gateway($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			if($_POST['email'] == "steve@austin.com" && $_POST['password'] == "admin12345"){

				$_SESSION['user'] = $_POST['email'];

				$title = "Welcome user " . $_POST['email'];

				$msg = "Access granted";

			} else {

				// Print error message
				$title = "Incorrect credentials provided " . $_POST['email'];

				$msg = "Access refused";

			}

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


	// --- Publisher (Order Submission) ---
	public function publishOrders()
	{
		$nats = new Nats('192.168.0.22', 4222); // Replace with your NATS server address

		try {
			$nats->connect();

			for ($i = 0; $i < 2; $i++) {
				$orderId = uniqid();
				$replyTo = "order.reply." . $orderId;

				$orderData = [
					'order_id' => $orderId,
					'customer_id' => rand(100, 200),
					'items' => ['item1', 'item2', 'item' . rand(3, 5)],
					'amount' => rand(50, 200),
				];

				$nats->publish('orders.process', json_encode($orderData), $replyTo);

				$nats->subscribe($replyTo, function ($subject, $data, $replyTo) use ($orderId) {
					$response = json_decode($data, true);
					if ($response['status'] === 'success') {
						echo "Order $orderId processed successfully. Transaction ID: " . $response['transaction_id'] . "\n";
					} else {
						echo "Order $orderId failed: " . $response['error'] . "\n";
					}
				});
			}
			$nats->wait(60); //wait for 60 seconds.
			$nats->close();

		} catch (\Exception $e) {
			echo "Publisher Error: " . $e->getMessage() . "\n";
		}
	}



	// --- Subscriber (Order Processing) ---
	public function processOrders()
	{
		$nats = new Nats('192.168.0.22', 4222); // Replace with your NATS server address

		try {
			$nats->connect();

			$nats->subscribe('orders.process', function ($subject, $data, $replyTo) {
				$order = json_decode($data, true);

				// Simulate order processing (CRM, payment)
				$success = (rand(0, 1) === 1);

				if ($success) {
					$response = [
						'status' => 'success',
						'transaction_id' => uniqid(),
					];
				} else {
					$response = [
						'status' => 'error',
						'error' => 'Payment failed',
					];
				}

				global $nats; // Access the Nats object
				$nats->publish($replyTo, json_encode($response));
			});

			// Keep the subscriber running indefinitely
			while (true) {
				sleep(1);
			}

			$nats->close();

		} catch (\Exception $e) {
			echo "Subscriber Error: " . $e->getMessage() . "\n";
		}
	}

	public function publishTestCustomer($data){

		$nats = new Nats('192.168.0.22','4222');

		try {

			$nats->connect();

			$nats->publish('order.test','foo');

			$nats->close();

		} catch(\Exception $e){

			$data = $this->set($data,'error-message',$e->getMessage());

		}

		return $data;

	}

	public function subscribeTestCustomer($data){

		$nats = new Nats('192.168.0.22','4222');

		try {

			$nats->connect();

			$nats->subscribe('order.test',function($subject,$data){

				$data = $this->set($data,'subject',$subject);

				$data = $this->set($data,'data',$data);

			});

			$nats->close();

		} catch(\Exception $e){

			$data = $this->set($data,'error-message',$e->getMessage());

		}

		return $data;

	}

    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			//$this->publishOrders();

			$debug = false;

			if($debug){

				$data = $this->publishTestCustomer($data);

			} else {

				$data = $this->subscribeTestCustomer($data);

			}

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