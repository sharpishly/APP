<?php

namespace App\Controllers;
use App\Core\Controller;
use dBug\dBug;

class Customerlover {

	public $title = "customerlover";

    public function index($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'CustomerloverModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' . __FUNCTION__, $data);		

    }

	public function insert($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'CustomerloverModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' . __FUNCTION__, $data);		

    }

	public function details($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'CustomerloverModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' . __FUNCTION__, $data);		

    }

	public function login($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'CustomerloverModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' . __FUNCTION__, $data);		

    }
}

?>