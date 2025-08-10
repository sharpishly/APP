<?php

namespace Session;

use dBug\dBug;

class Session {
    
    private $session_id;

    private $google = 'google_user_info';

    private $facebook = 'facebook';

    private $registration = 'registration';

    private $cart = 'cart';
    
    public function __construct() {
        
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }        
        
        $this->session_id = session_id();
    }

    public function authentication(){
        // new dBug($_SESSION);
        if
        (
            isset($_SESSION[$this->google]['user_logged_in']) ||
            isset($_SESSION[$this->facebook]['user_logged_in']) ||
            isset($_SESSION[$this->registration]['user_logged_in'])
        )
        {
            return true;
        }
        return false;
    }

    public function getRegistration($key) {
        if (isset($_SESSION[$this->registration][$key])) {
            return $_SESSION[$this->registration][$key];
        }
        return null;
    }
    
    public function setRegistration($key, $value) {
        $_SESSION[$this->registration][$key] = $value;
    }

    public function setRegistrationUser($value) {
        foreach($value as $key => $val){
            $_SESSION[$this->registration][$key] = $val;
        }
        
    }

    public function getGoogle($key) {
        if (isset($_SESSION[$this->google][$key])) {
            return $_SESSION[$this->google][$key];
        }
        return null;
    }
    
    public function setGoogle($key, $value) {
        $_SESSION[$this->google][$key] = $value;
    }

    public function getFacebook($key) {
        if (isset($_SESSION[$this->facebook][$key])) {
            return $_SESSION[$this->facebook][$key];
        }
        return null;
    }
    
    public function setFacebook($key, $value) {
        $_SESSION[$this->facebook][$key] = $value;
    }
    
    public function get($key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        }
        return null;
    }
    
    public function set($key, $value) {
        $_SESSION[$key] = $value;
    }
    
    public function __destruct() {
        session_write_close();
    }
    
    public function getUserId(){

        $fields = array(
            $this->google => 'id',
            $this->facebook  => 'id',
            $this->registration => 'id'
        );

        foreach($fields as $key => $value){

            // new dBug(array('key'=>$key,'value'=>$value));

            if(isset($_SESSION[$key][$value])){

                return $_SESSION[$key][$value];

            }
            
        }

        return null;
    }
    
    public function getTenantId(){
        return 101;
    }

}

?>