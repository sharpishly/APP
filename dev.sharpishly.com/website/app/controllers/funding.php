<?php

namespace App\Controllers;

use App\Core\Controller;

use dBug\dBug;

class Funding {

	public $title = 'funding';

	public function index($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont->setHeaders();

		echo json_encode($data);
		
	}

	public function create($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

	public function add($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

	public function records($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

	public function update($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

	public function modify($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

	public function details($id = ''){

		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);
				
		$cont = new Controller();
		
		$models = array(
			$data['model']=>'FundingModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['model']]->main($data,$models);

		$cont -> views( $data['model'] . '/' . __FUNCTION__, $data);		

		
	}

}

?>