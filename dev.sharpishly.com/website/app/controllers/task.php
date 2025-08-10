<?php

namespace App\Controllers;

use App\Core\Controller;

use dBug\dBug;

class Task {
	
	public function index ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'index',
			'no'=>$no,
			'model'=>'task'
		);
				
		$cont = new Controller();
		
		$models = array(
			'task'=>'TaskModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['task']->main($data,$models);		
				
		$this->setHeaders();
		
		// Send the response back as JSON
		echo json_encode($data);
	}

	public function setHeaders(){
		// Allow CORS requests from any origin
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
		header("Content-Type application:json");
	}

	public function create ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'create',
			'no'=>$no,
			'title'=>'create'
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'HomeModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont->view($data,'home/header');
		
		$cont->view($data,'home/main');
		
		$cont->view($data,'home/footer');		
	}

	public function read ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'read',
			'no'=>$no,
			'title'=>'read'
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'HomeModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont->view($data,'home/header');
		
		$cont->view($data,'home/main');
		
		$cont->view($data,'home/footer');		
	}

	public function update ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'update',
			'no'=>$no,
			'title'=>'update'
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'HomeModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont->view($data,'home/header');
		
		$cont->view($data,'home/main');
		
		$cont->view($data,'home/footer');		
	}

	public function delete ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'index',
			'no'=>$no,
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'HomeModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont->view($data,'home/header');
		
		$cont->view($data,'home/main');
		
		$cont->view($data,'home/footer');		
	}

	public function info ($id = '',$no = ''){
		phpinfo();
		die();	
	}
	
}

?>
