<?php

namespace App\Models;

use dBug\dBug;

use SimpleEmail\SimpleEmail;

use Zoho\Zoho;

class TodoModel extends Model {

    public function main($data,$models){

        $options = [];

		$data = $this->set($data,'tbl','migrate_todo');

		$data = $this->set($data,'tbl_notes','migrate_todo_notes');

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

		$data = $this->statuses($data,$models,$options);

		$data = $this->search($data,$models,$options);

		$data = $this->searching($data,$models,$options);

        // new dBug($data);

		// new dBug($_SESSION);

        // die();

        return $data;
    }

	public function searching_process($rs,$data){

		if(isset($rs['result'][0]['id'])){

			foreach($rs['result'] as $key => $value){


				$url = $this->helper->url('todo/update/' . $value['id']);

				$attr = array(
					'href'=>$url,
					'class'=>__FUNCTION__
				);

				$link = $this->attributes->set($attr);

				$value['link'] = $link;

				$rs['result'][$key] = $value;

			}

		}

		return $rs;
	}


	public function searching_process_link($rs,$data){

		if(isset($rs['result'][0]['id'])){

			foreach($rs['result'] as $key => $value){


				// $url = $this->helper->url('todo/update/' . $value['id']);

				$attr = array(
					'href'=>$value['url'],
					'class'=>__FUNCTION__,
					'target'=>'_blank'
				);

				$link = $this->attributes->set($attr);

				$value['external'] = $link;

				$rs['result'][$key] = $value;

			}

		}

		return $rs;
	}


	public function searching($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$conditions = array(
				'table'=>$data['tbl'],
				'like'=>array(
					'col'=>'title',
					'val'=>$_POST['search']
				)
			);
			
			$rs = $this->db->find($conditions);

			$rs = $this->check_if_records_exists($rs,$options);

			$rs = $this->searching_process($rs,$data);

			$rs = $this->searching_process_link($rs,$data);

			$data = $this->partials->spartials($data,__FUNCTION__,$rs['result']);
			
			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => $title,
				'h2' => 'Please enter your search below',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);

		}

		return $data;
	}

	public function search($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){
			
			$title = ucfirst(__FUNCTION__);

			$arr = array(
				'h1' => $title,
				'h2' => 'Please enter your search below',
				'title' => $title
			);

			$data = $this->partials->templates($data,$arr);

			$options['url'] = 'searching';

			$data = $this->form->set($data,$models,$options);
		}

		return $data;
	}

	public function get_statuses($data,$models,$options){

		$conditions = array();

		$data = $this->set($data,'post_status',$data['post']['status']);

		unset($data['post']['status']);

		foreach($data['post'] as $key => $value){

			$condition = array(
				'table'=>$data['tbl'],
				'update'=>array(
					'status'=>$data['post_status']
				),
				'where'=>array(
					'id'=>$key
				)
			);

			$conditions[] = $condition;

		}

		$data = $this->set($data,__FUNCTION__,$conditions);

		$rs = $this->db->mupdate($conditions);

		$data = $this->set($data,'mupdate',$rs);

		return $data;
	}

	public function statuses($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->get_statuses($data,$models,$options);

			$title = ucfirst(__FUNCTION__);

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Please ' . $title);
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->header_set_links($data,'funding-records','records');

			
		}
		
		return $data;
		
	}

	public function set_header_link($data,$models,$options){

		$fields = array(
			'add'=>'create',
			'login'=>'login',
			'records'=>'index',
			'search'=>'search'

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

			$url = 'google/auth/';

			$data = $this->create_link($data,$url,'google');

			$url = 'facebook/auth/';

			$data = $this->create_link($data,$url,'facebook');
			
		}
		
		return $data;
		
	}

	public function create_link($data,$url,$name){

		
        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>__FUNCTION__,
		);
        
        $attr = $this->attributes->set($arr);

		$data = $this->partials->template($data,$name,$attr);		

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

		$id = 0;

		if(isset($data['database_records']['result'][0]['status'])){

			$id = $data['database_records']['result'][0]['status'];

		}

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

	public function simple_email(){
			// Example 1: Basic email
			$email1 = new SimpleEmail();
			$email1->setTo("paultypekoce@gmail.com")
				->setSubject("Test Subject")
				->setMessage("Hello, this is a test email from SimpleEmail class.")
				->addHeader("From: paul@sharpishly.com");

			if ($email1->send()) {
				echo "Email 1 sent successfully!\n";
			} else {
				echo "Failed to send Email 1.\n";
			}


			echo "\n";

			// Example 2: HTML email with constructor and more headers
			$htmlMessage = "<html><body><h1>Hello!</h1><p>This is an <b>HTML</b> email.</p></body></html>";
			$headers = "From: paul@sharpishly.com\r\n";
			$headers .= "Reply-To: paul@sharpishly.com\r\n";
			$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

			$email2 = new SimpleEmail(
				"paultypekoce@gmail.com",
				"HTML Email Test",
				$htmlMessage,
				$headers
			);

			if ($email2->send()) {
				echo "Email 2 sent successfully!\n";
			} else {
				echo "Failed to send Email 2.\n";
			}		
	}

	public function zoho($data=false, $models=false,$options=false){

		$zoho = new Zoho(
			ZOHO_API_ID,
			ZOHO_API_SECRET,
			'12345',
			'https://mail.zoho.com'
		);

		try {
			// Send an email
			$response = $zoho->sendEmail(
				'from@sharpishly.com',
				'to@recipient.com',
				'Test Subject',
				'This is a test email body.',
				'cc@recipient.com'
			);
			echo 'Email sent successfully: ' . json_encode($response) . "\n";

			// Fetch received emails
			$emails = $zoho->receiveEmails(5);
			echo 'Received emails: ' . json_encode($emails, JSON_PRETTY_PRINT) . "\n";
		} catch (\Exception $e) {
			echo 'Error: ' . $e->getMessage() . "\n";
		}

		return $data;
	}

    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){
			
			$this->simple_email();		

			$data = $this->pagination($data);

			$data = $this->filter_records_by_id($data,$models,$options);
			
			$wheres = array(
				'status'=>'1',
				'or'=>array(
					'status'=>array(2,5)
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

			$rs = $this->set_record_checkbox($rs,'checkbox','update',$data);

			$data = $this->status_to_partials($data);

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

			$options['url'] = 'statuses';

			$data = $this->form->set($data,$models,$options);

			
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

	public function set_record_checkbox($rs,$partial,$name,$data){

		foreach($rs['result'] as $key => $value){


			$attr = array(
				'class'=>'_checkbox',
				'type'=>'checkbox',
				//'value'=>'Test',
				'id'=>$value['id'],
				'name'=>$value['id'],
				//'checked'=>'checked'
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