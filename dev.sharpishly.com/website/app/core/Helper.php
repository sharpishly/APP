<?php

namespace App\Core;

set_include_path(get_include_path() . PATH_SEPARATOR . 'phpseclib');

use dBug\dBug;

class Helper {
	
	public $dir;

	private $protocol;

    public function __construct(){
    	
		$this->dir = str_replace('public', 'app/', $_SERVER['DOCUMENT_ROOT']);

		// $arr = explode('/',$_SERVER['SERVER_PROTOCOL']);
		// new dBug($arr);
		$this->protocol = 'https';

    }

    public function domain(){
	
		$link =  $this->protocol . "://" . $_SERVER['HTTP_HOST']; //die();
		
        return $link;

    }

    public function url($url=false,$options=false){
    	    	
		$link = "/" . $url;
		
        return $link;

    }

	public function image($img){

		$link = "http://" . $_SERVER['HTTP_HOST'] . "/" . $img;
		
        return $link;

	}

	public function create_url(){
		
		$arr = func_get_args();
		
		return implode('/', $arr);
	}
}

?>