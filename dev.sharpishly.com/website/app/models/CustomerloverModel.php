<?php

namespace App\Models;

use dBug\dBug;

class CustomerloverModel extends Model {

    public function main($data,$models){

        $options = [];

		$data = $this->header($data);
			
		$data = $this->footer($data);

        $data = $this->index($data,$models,$options);

		$data = $this->insert($data,$models,$options);

		$data = $this->details($data,$models,$options);

		$data = $this->login($data,$models,$options);

		// new dBug($data);

        return $data;
    }

	public function insert($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$options['tbl'] = 'migrate_customerlover_details';
			
			$data = $this->save($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of todo');

			$data = $this->partials->template($data,'title','::Hub::');

			$url =$this->helper->url($data['model'] . "/details/" . $data['save']['inserted']);

			$data = $this->partials->template($data,'home',$url);
			
		}
		
		return $data;
		
	}

	public function set_tag($data,$key,$val){

		$item = $data['partials']['records_all'][0][$val];

		$data = $this->partials->template($data,$key,$item);

		return $data;
	}

	public function customers($data,$models=false,$options=false){

		$partials = [];

		$partial = array(
			'firstname'=>'Steve',
			'lastname'=>'Austin',
			'id'=>'0',
			'link'=>''
		);

		$partials[] = $partial;

		$data = $this->partials->spartials($data,__FUNCTION__,$partials);

		return $data;
	}

	public function details($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$data = $this->get_record_by_id($data,$models,$options);

			//$data = $this->get_note_by_id($data,$models,$options);
						
			$data = $this->set($data, 'title', $data['title']);

			$data = $this->set_tag($data,'h1','business_name');

			$data = $this->set_tag($data,'h2','owner_name');

			$data = $this->partials->template($data,'title',ucfirst($data['model']));

			$data = $this->partials->template($data,'add',$this->helper->url("todo/notes/" . $data['id']));

			//@TODO: Add customer partials to details.ctp
			$data = $this->customers($data);
			
		}
		
		return $data;
		
	}

	public function set_record_url($rs,$data=false){

		foreach($rs['result'] as $key => $value){

			$value['link'] = $this->helper->url('todo/details/' . $value['id']);

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function set_status_selection($rs,$data=false){

		foreach($rs['result'] as $key => $value){

			$url = 'customerlover/priority/' . $value['id'];
			
			$value['select'] = $this->helper->url($url);

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function get_record_status($value){


		foreach($this->form->statuses as $key => $val){

			if($val == $value['status']){

				$value['priority'] = $key;

			}

		}

		return $value;
	}

	public function set_record_status($rs,$data=false){

		foreach($rs['result'] as $key => $value){

			$value = $this->get_record_status($value);

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function get_record_by_id($data,$models,$options){

		$wheres = array(
			'id'=>$data['id']
		);
		
		$conditions = array(
			'table'=>'migrate_customerlover_details',
			//'order'=>array('id'=>'DESC'),
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->set_record_status($rs);

		$rs = $this->set_record_url($rs);

		$rs = $this->set_status_selection($rs);

		$data = $this->partials->spartials($data,'records',$rs['result']);

		return $data;
	}



    public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Business name'=>array('name'=>'business_name','placeholder'=>'What is your business name?','type'=>'text','required'=>'required'),
				'Owner name'=>array('name'=>'owner_name','placeholder'=>'What is your name?','type'=>'text','required'=>'required'),
				'Email'=>array('name'=>'email','placeholder'=>'What is your email?','type'=>'email','required'=>'required'),
				'Phone'=>array('name'=>'phone','placeholder'=>'What is your telephone number?','type'=>'tel','required'=>'required'),
			);

			$data = $this->fields($data,$rs,$options);
			
			$data = $this->set($data, 'title', $data['title']);

			$data = $this->header_set_links($data,'login','login');

			$data = $this->header_set_links($data,'register','index/1');

			$data = $this->header_set_links($data,'business','details/1');

			$options['url'] = 'funding/create/1';

			$data = $this->header_set_links($data,'funding','',$options);
			
		}
		
		return $data;
		
	}

	public function login($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			// Dummy link skipping over authentication functionality
			$options['url'] = 'details/1';

			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Email'=>array(
					'name'=>'email',
					'placeholder'=>'Please enter your email',
					'type'=>'email',
					'required'=>'required'
				),
				'Password'=>array(
					'name'=>'password',
					'placeholder'=>'Please enter your password',
					'type'=>'password',
					'required'=>'required'),
			);

			$data = $this->fields($data,$rs,$options);
			
			$data = $this->set($data, 'title', $data['title']);

			$data = $this->header_set_links($data,'login','login');

			$data = $this->header_set_links($data,'register','index/1');
			
		}
		
		return $data;
		
	}

}

?>