<?php

namespace App\Controllers;
use App\Core\Controller;
use dBug\dBug;
use Session\Session;
use FacebookSdk\FacebookSdk;
use App\Core\Helper;

class Facebook {

    public string $title = '';  // Use type declaration
    public Session $session;    // Use type declaration
    public FacebookSdk $facebook;  // Use type declaration
    public Helper $helper;      // Use type declaration

	public function __construct()
	{
        $arr = explode('\\', __CLASS__);
        $this->title = strtolower(end($arr)); // More robust way to get class name
        $this->session = new Session();
        $this->helper = new Helper();

        //  Important:  Move this to a config file or environment variables.  DO NOT hardcode credentials.
        $config = [
            'app_id' => FACEBOOK_API_ID,  //  <--- Replace with your actual App ID
            'app_secret' => FACEBOOK_API_SECRET, // <--- Replace with your actual App Secret
            'redirect_uri' => $this->helper->domain() . '/facebook/gateway', //  Corrected path
        ];

        $this->facebook = new FacebookSdk($config);
	}

    private function render(array $data): void // Use type declaration
    {
        $data['session'] = $_SESSION; // Access session data

        $cont = new Controller(); 

        $models = [
            $data['title'] => $cont->setModelName(__CLASS__),
        ];

        $models = $cont->model($models); 

        $data = $models[$data['title']]->main($data, $models);  // Assumed method

        $cont->views($data['title'] . '/' . $data['directive'], $data);
    }

    private function authentication(array $data): array  // Use type declaration
    {
        if ($this->session->getFacebook('user_logged_in') !== true) { // Use strict comparison
            $data['directive'] = 'login';
        }
        return $data;
    }


    public function auth(string $id = ''): void  // Use type declaration
    {
        $data = [
            'id' => $id,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
            'post' => $_POST,
            'session_class' => $this->session, //  Not used
        ];
		
		$debug = TRUE;

		if($debug){

			//  Redirect to Facebook login URL.  This is the correct approach.
			$loginUrl = $this->facebook->getLoginUrl(['email', 'public_profile']); // Specify scopes
			header('Location: ' . $loginUrl);
			exit;

		} else {

			if ($this->session->getFacebook('user_logged_in') === true) { // Use strict comparison
				$data['directive'] = 'profile';
			} else {
				//  Redirect to Facebook login URL.  This is the correct approach.
				$loginUrl = $this->facebook->getLoginUrl(['email', 'public_profile']); // Specify scopes
				header('Location: ' . $loginUrl);
				exit;
			}

		}

        $this->render($data);
    }

	public function set_user_session(){

		$this->session->setFacebook('first_name','jamie');

		$this->session->setFacebook('last_name','summers');

		$this->session->setFacebook('email','jamie@summers.com');

		$this->session->setFacebook('access_token','12345');

		$this->session->setFacebook('refresh_token','12345');
	}

    public function gateway(string $id = ''): void  // Use type declaration
    {
        $data = [
            'id' => $id,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
            'post' => $_POST,
        ];

        //  Handle the Facebook callback here.  This is where you process the code.
        if (isset($_GET['code'])) {
            $code = $_GET['code'];
            try {
                $tokenData = $this->facebook->getAccessToken($code);
                //  Important:  Store the access token and other data securely.
                $this->session->setFacebook('access_token', $tokenData['access_token']);
                $this->session->setFacebook('expires_in', time() + $tokenData['expires_in']); // Store as timestamp
                $userData = $this->facebook->getUserData(['id', 'name', 'email', 'first_name', 'last_name']);
                //  Store user data in session or database as appropriate
                $this->session->setFacebook('user_id', $userData['id']);
                $this->session->setFacebook('first_name', $userData['first_name']);
                $this->session->setFacebook('last_name', $userData['last_name']);
                $this->session->setFacebook('email', $userData['email']);
                $this->session->setFacebook('user_logged_in', true);

                //  Redirect to a success page (e.g., profile)
                header('Location: /facebook/profile'); //  Adjust the path as needed.
                exit;

            } catch (\Exception $e) {
                //  Handle errors (e.g., display an error message)
                echo 'Error: ' . $e->getMessage();
                exit; //  Important:  Stop execution after an error
            }
        } else {
            //  Handle the case where there's no code (e.g., user denied permission)
            echo 'Error: Facebook login failed.  No code provided.';
            exit;
        }

        $this->render($data); //  This render is likely not needed.
    }

    public function login(string $id = ''): void  // Use type declaration
    {
        $data = [
            'id' => $id,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
        ];

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