<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\Helper;
use App\Core\Db;
use dBug\dBug;
use GoogleAuthenticator\GoogleAuthenticator;
use UserStorage\UserStorage; // Ensure this is correctly used
use Session\Session;

class Google {

    public $title;
    public $redirect;
    public $userStorage; // UserStorage instance
    public $helper;
    public $session;
    public $db;
    public $enter = '/dashboard/index';

    public function __construct()
    {
        $this->session = new Session();
        $this->db = new Db();
        $arr = explode('\\',__CLASS__);
        $this->title = strtolower($arr[2]);
        $this->userStorage = new UserStorage($this->session,$this->db); // Pass Session and Db to UserStorage
        $this->helper = new Helper();
        $this->redirect = $this->helper->domain();
    }

    public function set($data,$key,$val){
        $data[$key] = $val;
        return $data;
    }

    public function request(){
        return  new GoogleAuthenticator(GOOGLE_CLIENT_ID,GOOGLE_CLIENT_SECRET,$this->redirect,$this->session);
    }

    public function google($data){
        if($this->userStorage->isLoggedIn()){
            // If already logged in, maybe redirect to dashboard or user profile
            header('Location: ' . $this->enter);
            exit();
        } else {
            $google = $this->request();
            // This line is no longer necessary as GoogleAuthenticator uses Session directly for tokens.
            // Remove: $this->session->setGoogle('request',$google);
            $google->login();
            // login() performs a header redirect and exits. No code below this will run.
        }
        return $data; // This line will only be reached if login() doesn't redirect
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

    public function callback($code = '', $scope = '')
    {
        $data = [
            'code' => $code,
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'post' => $_POST,
            'scope' => $scope,
        ];

        $google = $this->request(); // Create an instance of GoogleAuthenticator

        try {
            // Call GoogleAuthenticator's handleCallback to exchange code for tokens
            $google->handleCallback(['code' => $code, 'scope' => $scope]);

            // Get user info using the tokens now stored in session by handleCallback
            $userInfoFromGoogle = $google->getUserInfo();

            // IMPORTANT: Get the tokens that were stored by handleCallback in the session
            $accessToken = $this->session->get('google_access_token');
            $refreshToken = $this->session->get('google_refresh_token');

            // Prepare user info for UserStorage::storeUserInfo, mapping Google's common fields
            $processedUserInfo = [
                'email' => $userInfoFromGoogle['email'] ?? null,
                'first_name' => $userInfoFromGoogle['given_name'] ?? null, // Google often uses 'given_name'
                'last_name' => $userInfoFromGoogle['family_name'] ?? null, // Google often uses 'family_name'
                'picture' => $userInfoFromGoogle['picture'] ?? null,
                'full_name' => $userInfoFromGoogle['name'] ?? null // Example: combined name
            ];

            $this->userStorage->storeUserInfo($accessToken, $refreshToken, $processedUserInfo);

            // This session flag might also be redundant if UserStorage::isLoggedIn() is definitive.
            // Keeping it for now if other parts of your app rely on it:
            $this->session->set('google_user_logged_in', 1);

            header('Location: ' . $this->enter);
            exit();

        } catch (\Exception $e) {
            header('Location: /login?error=' . urlencode('Google login failed: ' . $e->getMessage()));
            exit();
        }

    }

    public function auth($id = ''){
        $data = array(
            'id'=>$id,
            'directive'=>__FUNCTION__,
            'title'=>$this->title,
            'model'=>$this->title,
            'post'=>$_POST,
            'session'=>$_SESSION,
            'cookie'=>$_COOKIE,
            'session_class'=>$this->session
        );
        $data = $this->google($data);
        $this->render($data);
    }

    public function csv()
    {
        $data = [
            'directive' => __FUNCTION__,
            'title' => $this->title,
            'model' => $this->title,
            'session' => $_SESSION,
            'cookie' => $_COOKIE,
        ];
        if($this->userStorage->isLoggedIn()){
            header('Location: ' . $this->enter);
            exit();
        } else {
            $google = $this->request();
            $google->login();
        }
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
        $this->render($data);
    }

    public function users($id = ''){
        $data = array(
            'id'=>$id,
            'directive'=>__FUNCTION__,
            'title'=>$this->title,
            'model'=>$this->title,
            'session' => $_SESSION,
            'cookie' => $_COOKIE,
            'user_storage'=>$this->userStorage,
            'user_session'=>$this->session
        );
        $this->render($data);
    }

    public function tokens($id = ''){
        $data = array(
            'id'=>$id,
            'directive'=>__FUNCTION__,
            'title'=>$this->title,
            'model'=>$this->title,
            'session' => $_SESSION,
            'cookie' => $_COOKIE,
            'user_storage'=>$this->userStorage,
            'user_session'=>$this->session
        );
        $this->render($data);
    }

    public function books($id = ''){
        $data = array(
            'id'=>$id,
            'directive'=>__FUNCTION__,
            'title'=>$this->title,
            'model'=>$this->title,
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