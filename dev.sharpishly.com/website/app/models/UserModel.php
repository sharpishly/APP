<?php

namespace App\Models;

use App\Core\Helper;
use App\Core\Db;
use dBug\dBug;
use App\Models\Model;
use Attributes\Attributes;
use Partials\Partials;
use Form\Form;
use Data\Data;


class UserModel extends Model{

	public $helper;	
	public $db;
	public $attributes;
	public $partials;
	public $form;
	public $data;

	public function __construct(){

		$this->helper = new Helper();			
		$this->db = new Db();
		$this->attributes = new Attributes();	
		$this->partials = new Partials();   
        $this->form = new Form();
		$this->data = new Data();

		
	}
	
	public function main($data,$models,$options=false){

		//TODO: Add explict url method
		$options['explict'] = TRUE;
			
		$list = array(
			'home'=>'/',
			'User create'=>'/user/create',
			'User read'=>'/user/read/1',
			'User update'=>'/user/update',
			'User delete'=>'/user/delete',
			'User register'=>'/user/register',			
			'phpinfo'=>$this->helper->url('home/info'),
		);
		
		$data = $this->set($data, 'list', $list);		

		$data = $this->index($data, $models, $options);

		$data = $this->create($data, $models, $options);

		$data = $this->register($data, $models, $options);

		$data = $this->read($data,$models,$options);

		$data = $this->login($data,$models,$options);

		$data = $this->duplicate($data,$models,$options);

		return $data;
	}

	public function status($save,$data=false){

		$save['date'] = date('Y-m-d h:m:s');
		
		$save['status'] = 1;
		
		return $save;
	}

	public function save($data,$options){

		$save = $this->status($data['response']['data']);

		$conditions = array(
			'table'=>'migrate_' . $data['id'] . '_details',
			'save'=>$save
		);

		$rs = $this->db->save($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}


	public function create($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'create'){

			$data = $this->save($data,$options);
			
			$conditions = array(
				'table'=>'migrate_' . $data['id'] . '_details',
				'order'=>array('id'=>'DESC'),
			);
			
			$rs = $this->db->find($conditions);

			if(isset($rs['result'][0])){

				$data = $this->set($data, 'user_record', $rs['result'][0]);


			} else {

				$data = $this->set($data, 'user_record', false);

			}
						
		}
		
		return $data;
		
	}
	
	public function duplicate($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$wheres = array("email"=>$data['email']);
			
			$conditions = array(
				'table'=>'migrate_registration_details',
				'wheres'=>$wheres,
			);
			
			$rs = $this->db->find($conditions);

			$data = $this->set($data, 'response', $rs);

			if(isset($rs['result'][0])){

				$data = $this->set($data, 'user_record', $rs['result'][0]);

			} else {

				$data = $this->set($data, 'user_record', false);

			}
						
		}

		return $data;
		
	}
	
	public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'index'){

			$data = $this->get_user_by_id($data,$options);

			$data = $this->get_company_by_id($data,$options);

			$data = $this->get_personal_by_id($data,$options);

			$data = $this->get_project_by_id($data,$options);
						
		}
		
		return $data;
		
	}

	public function login($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'login'){

			$wheres = array(
				"email"=>$data['request']['data']['email'],
				"password"=>$data['request']['data']['password']
			);
			
			$conditions = array(
				'table'=>'migrate_registration_details',
				'wheres'=>$wheres,
				//'limit'=>'0,10'
			);
			
			$rs = $this->db->find($conditions);

			$data = $this->set($data, 'response', $rs);

			if(isset($rs['result'][0])){

				$data = $this->set($data, 'user_record', $rs['result'][0]);

			} else {

				$data = $this->set($data, 'user_record', false);

			}
						
		}
		
		return $data;
		
	}


	public function get_personal_by_id($data,$options){

		$wheres = array(
			"userid"=>$data['id'],
			'status'=>1
		);
			
		$conditions = array(
			'table'=>'migrate_personal_details',
			'wheres'=>$wheres,
			'field_names'=>array(
				'id',
				'firstname',
				'lastname',
				'registration_number',
				'industry_sector')
			//'limit'=>'0,10'
		);
		
		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0])){

			$data = $this->set($data, __FUNCTION__, $rs['result'][0]);

		} else {

			$data = $this->set($data, __FUNCTION__, false);

		}

		return $data;

	}

	public function get_project_by_id($data,$options){

		$wheres = array(
			"userid"=>$data['id'],
			'status'=>1
		);
			
		$conditions = array(
			'table'=>'migrate_project_details',
			'wheres'=>$wheres,
			'field_names'=>array('name','pref','description')
		);
		
		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0])){

			$data = $this->set($data, __FUNCTION__, $rs['result']);

		} else {

			$data = $this->set($data, __FUNCTION__, false);

		}

		return $data;

	}

	public function get_company_by_id($data,$options){

		$wheres = array(
			"userid"=>$data['id'],
			'status'=>1
		);
			
		$conditions = array(
			'table'=>'migrate_company_details',
			'wheres'=>$wheres,
			'field_names'=>array('id','name','registration','sector')
		);
		
		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0])){

			$data = $this->set($data, __FUNCTION__, $rs['result'][0]);

		} else {

			$data = $this->set($data, __FUNCTION__, false);

		}

		return $data;

	}

	public function get_user_by_id($data,$options){

		$wheres = array(
			"id"=>$data['id'],
			'status'=>1
		);
			
		$conditions = array(
			'table'=>'migrate_registration_details',
			'wheres'=>$wheres,
			//'limit'=>'0,10'
		);
		
		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0])){

			$data = $this->set($data, __FUNCTION__, $rs['result'][0]);

		} else {

			$data = $this->set($data, __FUNCTION__, false);

		}

		return $data;

	}


	public function read($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'read'){

			$wheres = array("id"=>$data['id']);
			
			$conditions = array(
				'table'=>'migrate_registration_details',
				'wheres'=>$wheres,
				//'limit'=>'0,10'
			);

			$conditions = $this->joins($data,$conditions);
			
			$rs = $this->db->find($conditions);

			$data = $this->set($data, 'response', $rs);

			if(isset($rs['result'][0])){

				$data = $this->set($data, 'user_record', $rs['result'][0]);

			} else {

				$data = $this->set($data, 'user_record', false);

			}
						
		}

		//new dBug($data);
		
		return $data;
		
	}

	public function joins($data,$conditions,$options=false){
		
		$conditions['joins']=array(						
			array(
				'type'=>'INNER',
				'on'=>array(
					'tbl1'=>'id',
					'tbl2'=>'userid'
				),
				'table'=>'migrate_personal_details',
				'fields'=>array(
					'id'=>'personal_id',
					'firstname'=>'firstname',
					'lastname'=>'lastname'
				)
			),							
		);
		
		return $conditions;
		
	}	

	/**
	 * @param $data, $models, $options
	 **/
	public function registration_form($data,$models=false,$options=false){

		$url =  $this->helper->create_url($this->data->get($data,'model'),'create')	;

        $arr = array(
        	'action'=>$this->helper->url($url),
        	'method'=>'POST',
		);
        
        $form = $this->attributes->set($arr);
        
        $data = $this->partials->template($data, 'form', $form);
		
		return $data;
	}

	public function field($rs,$data){
    			
		foreach ($rs as $key => $value) {
			
	        $attr = array(
	        	'name'=>strtolower($value['title']),
	        	'id'=>strtolower($value['title']),
	        	'type'=>$value['type'],
	        	'placeholder'=>strtolower($value['description']),
	        	'class'=>'form-control',
	        	'aria-label'=>$value['title'],
	        	'aria-describedby'=>'basic-addon1'
			);
				        
	        $value['input'] = $this->attributes->set($attr);
			
			
			 $rs[$key] = $value;			
		}

        return $rs;
    }

	public function fields($data,$models=false,$options=false){
		
		$rs = array(
			array('title'=>'email','description'=>'Email','type'=>'email'),
			array('title'=>'password','description'=>'Password','type'=>'password')
		);
				
		$rs = $this->field($rs, $data);
		
		$data = $this->partials->spartials($data, __FUNCTION__, $rs);
		
		return $data;
	}


	public function register($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'register'){

			$title = "registration";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->registration_form($data,$models,$options);

			$data = $this->fields($data);
			
						
		}
		
		return $data;
		
	}

	
}

?>