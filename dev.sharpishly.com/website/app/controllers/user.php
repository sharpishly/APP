<?php

namespace App\Controllers;

use App\Core\Controller;

use dBug\dBug;

class User {

	public function test($id = false){

		$cont = new Controller();

		$cont->setHeaders();

		$requestBody = file_get_contents("php://input");

		$data = json_decode($requestBody, true);

		if($data){
			$response = [
				"status" => "success",
				"data" => $data,
				"message"=>"Sucess message here"
			];

		} else {
			$response = [
				"status" => "error",
				"message"=>"Error message here"
			];

		}

		// Send the response back as JSON
		echo json_encode($response);

		// Die just in case
		die();

	}
	
	public function index ($id = '',$no = ''){

		$data = array(
			'id'=>$id,
			'directive'=>'index',
			'no'=>$no,
			'title'=>'index'
		);
				
		$cont = new Controller();
		
		$models = array(
			'user'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['user']->main($data,$models);
		
		$cont->setHeaders();
		
		// Dummy data to simulate a user response
		$response = [
			"status" => "success",
			"data" => $data
		];
		
		// Send the response back as JSON
		echo json_encode($response);

		// Die just in case
		die();
				
	}

	public function request(){

		$cont = new Controller();

		$cont->setHeaders();

		$requestBody = file_get_contents("php://input");

		$data = json_decode($requestBody, true);

		if($data){
			$response = [
				"status" => "success",
				"data" => $data,
				"message"=>"Sucess message here"
			];

		} else {
			$response = [
				"status" => "error",
				"data" => array(),
				"message"=>"Error message here"
			];

		}

		return $response;

	}	

	public function create ($id = '',$no = ''){

		$response = $this->request();

		$data = array(
			'id'=>$id,
			'directive'=>'create',
			'no'=>$no,
			'title'=>'create',
			'response'=>$response
		);

		$cont = new Controller();
		
		$models = array(
			'home'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);

		$response['save'] = $data['save'];

		// Send the response back as JSON
		echo json_encode($data);
		
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
			'home'=>'UserModel'
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
			'home'=>'UserModel'
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
			'home'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont->view($data,'home/header');
		
		$cont->view($data,'home/main');
		
		$cont->view($data,'home/footer');		
	}

	public function register ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'model'=>'user',
			'no'=>$no,
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		
	}

	public function duplicate ($email = ''){
		
		$data = array(
			'email'=>$email,
			'directive'=>__FUNCTION__,
			'model'=>'user',
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);
		
		$cont->setHeaders();
				
		// Send the response back as JSON
		echo json_encode($data);		
	}



	public function login ($id = '',$no = ''){

		$response = $this->request();
		
		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'model'=>'user',
			'no'=>$no,
			'request'=>$response
		);
				
		$cont = new Controller();
		
		$models = array(
			'home'=>'UserModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['home']->main($data,$models);		
				
		// Send the response back as JSON
		echo json_encode($data);		
	}

	public function info ($id = '',$no = ''){
		phpinfo();
		die();	
	}
	
}

?>
