<?php

namespace App\Models;

use dBug\dBug;

class FundingModel extends Model{

    public function main($data,$models,$options=false){

		$data = $this->header($data);
			
		$data = $this->footer($data);

        $data =$this->index($data,$models,$options);

        $data = $this->create($data,$models,$options);

		$data = $this->add($data,$models,$options);

		$data = $this->records($data,$models,$options);

		$data = $this->update($data,$models,$options);

		$data = $this->modify($data,$models,$options);

		$data = $this->details($data,$models,$options);


		// new dBug($data);

        return $data;

    }

	public function modify($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$options['tbl'] = 'migrate_funding';
			
			$data = $this->updates($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of todo');

			$data = $this->partials->template($data,'title','::Hub::');

			$url =$this->helper->url($data['model'] . "/details/" . $_POST['id']);

			$data = $this->partials->template($data,'home',$url);
			
		}
		
		return $data;
		
	}

	public function update($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$options['restrict'] = array('content','date');

			$data = $this->get_record_by_id($data,$models,$options);

			$data = $this->create_form_from_partial($data,$models,$options);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update Funding');

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');

			$options['url'] = 'modify';

			$data = $this->form->set($data,$models,$options);
			
		}
		
		return $data;
		
	}

	public function get_record_by_id($data,$models,$options){

		$wheres = array(
			'id'=>$data['id']
		);
		
		$conditions = array(
			'table'=>'migrate_funding',
			//'order'=>array('id'=>'DESC'),
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$data = $this->set($data,'database_records',$rs);

		// $rs = $this->set_record_status($rs);

		$rs = $this->set_record_url($rs);

		// $rs = $this->set_status_selection($rs);

		$data = $this->partials->spartials($data,'records',$rs['result']);

		return $data;
	}

  	public function fields($data,$rs,$options=false){
			
		$arr = array();

		foreach($rs as $key => $val){

			$attr = $this->attributes->set($val);

			$arr[] = array(
				'title'=>$key,
				'attr'=>$attr
			);

			
		}

		$data = $this->partials->spartials($data,'fields',$arr);

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
					'name'=>'Description',
					'placeholder'=>'Provide a description?',
					'type'=>'text',
					'required'=>'required'
				),
				'Web Address'=>array(
					'name'=>'url',
					'placeholder'=>'Provide a description?',
					'type'=>'text',
					'required'=>'required',
				),

			);

			$data = $this->fields($data,$rs,$options);

			
		}
		
		return $data;
		
	}

	public function status($save,$data=false){

		$save['date'] = date('Y-m-d h:m:s');
		
		$save['status'] = 1;
		
		return $save;
	}

	public function add($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){
			
			$save = $this->status($_POST);

			$conditions = array(
				'table'=>'migrate_funding',
				'save'=>$save
			);
	
			$rs = $this->db->save($conditions);

			$data = $this->set($data,'save',$rs);

			$data = $this->set($data,'conditions',$conditions);

			$title = "Add funding source";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');

			$url = $this->helper->url($data['model'] . "/records");

			$attr = array(
				'href'=>$url,
				'id'=>'link'
			);

			$link = $this->attributes->set($attr);
                        
            $data = $this->partials->template($data, 'url', $link);
			
		}
		
		return $data;
		
	}

	public function details($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			$wheres = array(
				'id'=>$data['id']
			);
			
			$conditions = array(
				'table'=>'migrate_funding',
				'order'=>array('id'=>'DESC'),
				'wheres'=>$wheres
			);

			$rs = $this->db->find($conditions);

			$rs = $this->set_record_url($rs);
				
			$data = $this->partials->spartials($data,'records',$rs['result']);


			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update Funding');

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}


	public function records($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){
			
			$conditions = array(
				'table'=>'migrate_funding',
				'order'=>array('id'=>'DESC'),
				//'limit'=>'0,10'
			);

			$rs = $this->db->find($conditions);

			$rs = $this->set_record_url($rs);
				
			$data = $this->partials->spartials($data,'records',$rs['result']);
			
		}
		
		return $data;
		
	}

	public function set_record_url($rs,$data=false){

		foreach($rs['result'] as $key => $value){

			$url = $this->helper->url('funding/update/' . $value['id']);

			$attr = array(
				'href'=>$url,
				'class'=>'_link'
			);

			$link = $this->attributes->set($attr);

			$value['link'] = $link;

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){
			
			$conditions = array(
				'table'=>'migrate_funding',
				'order'=>array('id'=>'DESC'),
				'limit'=>'0,10'
			);
			
			$rs = $this->db->find($conditions);
			
			//$rs = [];
			
			$data = $this->set($data, 'funding', $rs);
			
			$data = $this->set($data, 'title', 'Home');

			
		}
		
		return $data;
		
	}


}

?>