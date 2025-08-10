<?php

namespace App\Models;

use App\Core\Helper;

use App\Core\Db;

use dBug\dBug;

use App\Models\Model;


class TaskModel extends Model{

	public $helper;
	
	public $db;

	public function __construct(){

		$this->helper = new Helper();
				
		$this->db = new Db();
		
	}
	
	public function main($data,$models,$options=false){

		//TODO: Add explict url method
		$options['explict'] = TRUE;
			
		$list = array(
			'home/home'=>'/',			
		);
		
		$data = $this->set($data, 'list', $list);		

		$data = $this->index($data, $models, $options);
		
		return $data;
	}
	
	public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'index'){
			
			$conditions = array(
				'table'=>'migrate_business_plan_tasks',
				'order'=>array('id'=>'ASC'),
				'field_names'=>array('id','month','TaskDescription','Priority'),
				'limit'=>'0,12'
			);
			
			$rs = $this->db->find($conditions);
						
			$data = $this->set($data, 'task', $rs);
			
			$data = $this->set($data, 'title', 'Task');
			
		}
		
		return $data;
		
	}
		
}

?>