<?php

namespace App\Controllers;
use App\Core\Controller;
use Session\Session;
use dBug\dBug;

class Home {

	public $title = '';

	public $session;

	public function __construct()
	{
		$arr = explode('\\',__CLASS__);

		$this->title = strtolower($arr[2]);

		$this->session = new Session();

		//new dBug($this->session);
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

	public function authentication($data){

		if(!isset($_SESSION['user'])){

			$data['directive'] = 'login';

		}

		return $data;
	}

    public function gateway($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function login($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,

		);

		$this->render($data);

    }

    public function index($start = '0',$limit = '10',$sort = 'DESC'){

        $data = array(
            'start'=>$start,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'limit'=>$limit,
			'sort'=>$sort

		);

		$data = $this->authentication($data);

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