<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\Db;
use dBug\dBug;
use Session\Session;

class Sharpishly {

	public $title = '';

	public $session;

	public $db;

	public function __construct()
	{
		$arr = explode('\\',__CLASS__);

		$this->title = strtolower($arr[2]);

		$this->session = new Session();

		$this->db = new Db();

	}

	public function render($data){

		$cont = new Controller();
		
		$models = array(
			$data['title']=>$cont->setModelName(__CLASS__),
			'migrations'=>'MigrationsModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' .$data['directive'], $data);	

	}

	public function authentication($data){

		if($this->session->authentication() !== true){

			$data['directive'] = 'login';

			$data[__FUNCTION__] = 'failed';

		}

		return $data;
	}

    public function register($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$email = $_POST['email'];

		$wheres = array(
			'email'=>$email
		);
		
		$conditions = array(
			'table'=>'migrate_users',
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		// new dBug($rs['result'][0]['id']);

		if(isset($rs['result'][0]['id'])){

			$data['directive'] = 'registration';

			$msg = array(
				'error'=>'duplicate entry',
				'email'=>$email
			);

			$data['redirect'] = $msg;

		}
		
		// die();

		$this->render($data);

    }

    public function seed($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		// $data = $this->authentication($data);

		// new dBug($data);die();
		
		$this->render($data);

    }

    public function start($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		// $data = $this->authentication($data);

		// new dBug($data);die();
		
		$this->render($data);

    }

    public function updatecart($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);
		
		$this->render($data);

    }

    public function buy($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		// new dBug($data);die();

		$data = $this->authentication($data);

		$this->render($data);

    }

    public function membership($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		// new dBug($data);die();

		$data = $this->authentication($data);

		$this->render($data);

    }

    public function updatecartitem($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);

		$this->render($data);

    }

    public function registration($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function basket($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);

		$this->render($data);

    }

    public function checkout($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);

		$this->render($data);

    }

	public function if_duplicate($data){

		// new dBug($_POST);die();
		$wheres = array(
			'product_id'=>$_POST['product_id'],
			'status'=>1,
			'user_id'=>$this->session->getUserId()
		);

		$conditions = array(
			'table'=>'migrate_products_cart',
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		
		if(isset($rs['result'][0]['id'])){

			$data['directive'] = 'shop';

			$msg = array(
				'error'=>'duplicate entry',
				//'email'=>$email
			);

			$data['redirect'] = $msg;


		}

		return $data;
	}

    public function cart($id = '',$product = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);

		$data = $this->if_duplicate($data);

		$this->render($data);

    }


    public function terms(string $id = ''): void  // Use type declaration
    {
        $data = [
            'id' => $id,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
        ];

        $this->render($data);
    }

    public function policy(string $id = ''): void  // Use type declaration
    {
        $data = [
            'id' => $id,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
        ];

        $this->render($data);
    }


    public function collectivebargaining($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function customercare($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function debtmanagement($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function businessinabox($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function taxrebate($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function tradeonly($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function portal($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$this->render($data);

    }

    public function home($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$data = $this->authentication($data);

		$this->render($data);

    }

    public function gateway($id = ''){

        $data = array(
            'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title,
			'post'=>$_POST

		);

		$conditions = array(
			'table'=>'migrate_users',
			'wheres'=>$_POST
		);

		$rs = $this->db->find($conditions);

		if(isset($rs['result'][0]['id'])){

			$this->session->setRegistrationUser($rs['result'][0]);

			$this->session->setRegistration('user_logged_in',TRUE);

			$data['directive'] = 'home';

		} else {

			$data['directive'] = 'login';

		}

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

	public function shop($shop_id=''){

        $data = array(
			'shop_id'=>$shop_id,
			'directive'=>__FUNCTION__,
			'title'=>$this->title,
			'model'=>$this->title
		);

		$data = $this->authentication($data);

		$this->render($data);
		

    }
}

?>