<?php

namespace App\Models;
use Google\Google;
use dBug\dBug;
use FFI;
use Session\Session;
use GoogleAuthenticator\GoogleAuthenticator;
use UserStorage\UserStorage;

class GoogleModel extends Model {

	public $session;
	public $redirect;
    public $userStorage;
	public $google;

    public function request(){
        return  new GoogleAuthenticator(GOOGLE_CLIENT_ID,GOOGLE_CLIENT_SECRET,$this->redirect,$this->session, $this->userStorage); // Pass UserStorage
    }


    public function main($data,$models){

		$this->session = new Session();

		$this->userStorage = new UserStorage($this->session,$this->db);

		$this->google = $this->request();

        $this->redirect = $this->helper->domain();

        $options = [];

		$data = $this->set($data,'tbl','migrate_users');

		$data = $this->set($data,'tbl_notes','migrate_users_notes');

		$data = $this->set($data,'tbl_tokens','migrate_user_tokens');

		$data = $this->header($data);

		$data = $this->header_set_links($data,'add','create',$options);

		$data = $this->header_set_links($data,'login','login',$options);

		$data = $this->header_set_links($data,'books','books',$options);

		$data = $this->header_set_links($data,'csv','csv',$options);

		$data = $this->header_set_links($data,'users','users',$options);

		$data = $this->header_set_links($data,'tokens','tokens',$options);
			
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

		$data = $this->books($data,$models,$options);

		$data = $this->auth($data,$models,$options);

		$data = $this->callback($data,$models,$options);

		$data = $this->csv($data,$models,$options);

		$data = $this->tokens($data,$models,$options);

		$data = $this->users($data,$models,$options);

		$data = $this->set($data,'COOKIE',$_COOKIE);

		$data = $this->set($data,'SESSION',$_SESSION);

		new dBug($data);

        return $data;
    }


	public function tokens($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$session = $data['user_session'];

			$title = ucfirst(__FUNCTION__);

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Please ' . $title);
                        
            $data = $this->partials->template($data, 'title', $title);

			$conditions = array(
				'table'=>'migrate_user_tokens',
				'order'=>array('user_id'=>'DESC'),
				'limit'=>'0,1'
			);
	
			$rs = $this->db->find($conditions);

			print_r($rs);
	
			$data = $this->set($data,__FUNCTION__,$rs);
		}
		
		return $data;
		
	}


	public function users($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$title = ucfirst(($data['directive']));
			
			$conditions = array(
				'table'=>$data['tbl'],
				'limit'=>'0,10'
			);

			$rs = $this->db->find($conditions);

			$data = $this->set($data,__FUNCTION__,$rs);

			$data = $this->partials->template($data,'h1',$title);

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title',$title);
			
		}
		
		return $data;
		
	}

	public function csv($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			//@TODO: Csv functionality

			new dBug($google = $this->google);

			new dBug($google->createCsvOnDrive());

			// new dBug($google->addCalendarEvent());

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}

	public function check_user_session($data){

		$fields = array('email','first_name','last_name');

		$rs = array();

		foreach($fields as $field){

			if(empty($this->session->getGoogle($field))){

				$rs[$field] = 'null';

			}

		}

		$data = $this->set($data,__FUNCTION__,$rs);
		
		return $data;
	}

	public function set_user_session($data){

		if(isset($data['check_user_session'])){

			$item = 'google_user_info';

			if(isset($_COOKIE[$item])){

				$fields = json_decode($_COOKIE[$item],TRUE);

				foreach($fields as $key => $value){

					// new dBug(array('key'=>$key,'value'=>$value));

					$this->session->setGoogle($key,$value);

				}

			}

		}

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


	public function set_referal_link($data,$models=false,$options=false){

		// $url = parse_url($_SERVER['HTTP_REFERER']);

		// $path = explode('/',$url['path']);

		// $link = 'todo/login';

		// if(isset($path[1])){

		// 	$link = $path[1] . '/home';

		// }

		$link = 'sharpishly/index/1';

		$url = $this->helper->url($link);

		$attr = array(
			'href'=>$url,
			'id'=>'link'
		);

		$link = $this->attributes->set($attr);
					
		$data = $this->partials->template($data, __FUNCTION__, $link);
		return $data;
	}

	public function callback($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$data = $this->set_referal_link($data);

			$data = $this->check_user_session($data);

			$data = $this->set_user_session($data);

			$data = $this->if_user_exists($data,$models,$options);

			$data = $this->save_new_user($data,$models,$options);

			//@TODO: Create unique access_token & refresh_token pages
			$data = $this->save_new_user_tokens($data,$models,$options);

			$data = $this->get_new_user_tokens($data,$models,$options);

			$title = ucfirst($data['title']);

			$first_name = $this->session->getGoogle('first_name');

			$last_name = $this->session->getGoogle('last_name');

			$fields = array(
				'h1'=>$first_name . ' ' . $last_name,
				'h2'=>'Provide an description of ' . $title,
				'title'=>$title
			);

			foreach($fields as $key => $value){

				$data = $this->partials->template($data,$key,$value);

			}

		}
		
		return $data;
		
	}

	public function auth($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){
			$title = ucfirst($data['title']);

			$fields = array(
				'h1'=>'Books records',
				'h2'=>'Provide an description of ' . $title,
				'title'=>$title
			);

			foreach($fields as $key => $value){

				$data = $this->partials->template($data,$key,$value);

			}

		}
		
		return $data;
		
	}

	public function books($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$apikey = GOOGLE_APIKEY;

			$google = new Google($apikey);

			new dBug($google->searchBooks('*',5));

			$title = ucfirst($data['title']);

			$fields = array(
				'h1'=>'Books records',
				'h2'=>'Provide an description of ' . $title,
				'title'=>$title
			);

			foreach($fields as $key => $value){

				$data = $this->partials->template($data,$key,$value);

			}

		}
		
		return $data;
		
	}

	public function gateway($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			if($_POST['email'] == "steve@austin.com" && $_POST['password'] == "admin12345"){

				unset($_SESSION['user']);

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){
			
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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

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
		
		if(isset($data['directive']) && $data['directive'] === 'index'){

			$data = $this->pagination($data);
			
			$wheres = array(
				'status'=>'1',
				'or'=>array(
					'status'=>array(2,3,5)
				)
			);

			$conditions = array(
				'table'=>$data['tbl'],
				'order'=>array('id'=>'DESC'),
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