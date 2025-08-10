<?php

namespace App\Controllers;

use App\Core\Controller;

use dBug\dBug;

class Migrations {
	
	public function index ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'index',
			'no'=>$no,
			'title'=>'migrations'
		);
				
		$cont = new Controller();
		
		$models = array(
			'migrations'=>'MigrationsModel'
		);
		
		$models = $cont->model($models);

		$this->render($data,$models,$cont);
		
	
	}

	public function read ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'read',
			'no'=>$no,
			'title'=>'migrations'
		);
				
		$cont = new Controller();
		
		$models = array(
			'migrations'=>'MigrationsModel'
		);
		
		$models = $cont->model($models);

		$this->render($data,$models,$cont);
		
	
	}

	public function save ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>'save',
			'no'=>$no,
			'title'=>'migrations'
		);
				
		$cont = new Controller();
		
		$models = array(
			'migrations'=>'MigrationsModel'
		);
		
		$models = $cont->model($models);

		$this->render($data,$models,$cont);
		
	
	}

	public function render($data,$models,$cont){

		$data = $models['migrations']->main($data,$models);		
				
		$cont->view($data,'migrations/header');
		
		$cont->view($data,'migrations/main');
		
		$cont->view($data,'migrations/footer');	
	}

}

?>
