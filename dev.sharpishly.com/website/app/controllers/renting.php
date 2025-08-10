<?php

namespace App\Controllers;
use App\Core\Controller;
use dBug\dBug;

class Renting {

	public $title = '';

	public function __construct()
	{
		$arr = explode('\\',__CLASS__);

		$this->title = strtolower($arr[2]);


	}

	public function render($data){

		$cont = new Controller();
		
		$models = array(
			$data['title']=>$cont->setModelName(__CLASS__)
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' .$data['directive'], $data);	
	}

    public function index($start = '0',$limit = '10'){

        $data = array(
            'start'=>$start,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'limit'=>$limit

		);

		$this->render($data);

    }

	public function notes($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
	

    }

	public function add_note($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
	

    }

	public function create($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
	

    }

	public function update($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
		

    }

	public function modify($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
	

    }

	public function add($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
		

    }

	public function details($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$this->render($data);
		

    }
}

?>