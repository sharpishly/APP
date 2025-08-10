<?php

namespace App\Models;

use dBug\dBug;
use Session\Session;


class DashboardModel extends Model {

	public $session;

    public function main($data,$models){

		$this->session = new Session();

        $options = [];

		$data = $this->set($data,'tbl','migrate_users');

		$data = $this->set($data,'tbl_notes','migrate_users_notes');

		$data = $this->set($data,'tbl_tokens','migrate_user_tokens');

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

        // new dBug($data);

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


	public function if_user_exists($data,$model=false,$options=false){

		$wheres = array(
			'email'=>$this->session->getGoogle('email')
		);

		$conditions = array(
			'table'=>$data['tbl'],
			'wheres'=>$wheres
		);

		$data = $this->set($data,__FUNCTION__ . '_conditions',$conditions);

		$rs = $this->db->find($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}


	public function save_new_user($data,$model=false,$options=false){

		if(!isset($data['if_user_exists']['result'][0])){

			$save = array(
				'first_name'=>$this->session->getGoogle('first_name'),
				'last_name'=>$this->session->getGoogle('last_name'),
				'email'=>$this->session->getGoogle('email'),
				'date'=>date('Y-m-d h:m:s'),
				'pref'=>'google',
				'status'=>1
			);
	
			$conditions = array(
				'table'=>$data['tbl'],
				'save'=>$save
			);
			// new dBug($conditions);die();
			$rs = $this->db->save($conditions);
	
			$data = $this->set($data,__FUNCTION__,$rs);

		}

		return $data;
	}



	public function save_new_user_tokens($data,$model=false,$options=false){

		if(isset($data['if_user_exists']['result'][0])){

			$date = date('Y-m-d h:m:s');

			$user_id = $data['if_user_exists']['result'][0]['id'];

			$save = array(
				'access_token'=>$this->session->getGoogle('access_token'),
				'refresh_token'=>$this->session->getGoogle('refresh_token'),
				'user_id'=>$user_id,
				'expiry_time'=>$date,
				'created_at'=>$date,
				'updated_at'=>$date,
				'pref'=>'google',
				'status'=>1
			);
	
			$conditions = array(
				'table'=>$data['tbl_tokens'],
				'save'=>$save
			);
	
			$rs = $this->db->save($conditions);
	
			$data = $this->set($data,__FUNCTION__,$rs);

		}

		return $data;
	}


	public function get_new_user_tokens($data,$model=false,$options=false){

		if(isset($data['if_user_exists']['result'][0])){

			//$session = $data['session_class'];

			$user_id = $data['if_user_exists']['result'][0]['id'];

			$wheres = array(
				'user_id'=>$user_id,
			);
	
			$conditions = array(
				'table'=>$data['tbl_tokens'],
				'wheres'=>$wheres,
				'order'=>array('id'=>'DESC')
			);
	
			$rs = $this->db->find($conditions);
	
			$data = $this->set($data,__FUNCTION__,$rs);

			$this->session->setGoogle('user_logged_in',TRUE);

		}

		return $data;
	}


    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->if_user_exists($data);

			$data = $this->save_new_user($data);

			//@TODO: Create unique access_token & refresh_token pages
			$data = $this->save_new_user_tokens($data,$models,$options);

			$data = $this->get_new_user_tokens($data,$models,$options);

			$title = ucfirst($data['title']);

			$first_name = $this->session->getGoogle('first_name');

			$last_name = $this->session->getGoogle('last_name');

			$arr = array();

			$link = $this->quick_link('sharpishly/index');


			$fields = array(
				'h1'=>$first_name . ' ' . $last_name,
				'h2'=>'Welcome to your dashboard. As you interact this page will grow with more useful information',
				'title'=>$title,
				'link'=>$link
			);

			foreach($fields as $key => $value){

				$data = $this->partials->template($data,$key,$value);

			}

			
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