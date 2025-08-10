<?php

namespace App\Core;

use Partials\Partials;
use dBug\dBug;
use Smarty\Smarty;


class Controller {

	protected $dir;

	public $smarty;

	public function __construct() {

		$this -> dir = dirname(dirname(__FILE__));
		
		$base =  dirname(dirname(__FILE__));

		$this->dir = $base . "/";

		$this->smarty = new Smarty($this);

	}
	
	public function setHeaders(){
		// Allow CORS requests from any origin
		header("Access-Control-Allow-Origin: *");
		header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Authorization");
		header("Content-Type application:json");
	}

	public function setModelName($classname){

		$arr = explode('\\',$classname);

		return $arr[2]. 'Model';

	}

	public function model($models) {

		foreach ($models as $key => $val) {

			require_once $this -> dir . '/models/' . $val . '.php';

			$obj = 'App\\Models\\' . $val;

			$models[$key] = new $obj;

		}

		return $models;

	}

	public function view($data, $path) {

		extract($data);
		
		require_once $this -> dir . '/view/' . $path . '.php';

	}
	
	public function views($view,$data){
	
			$view = strtolower($view);
	
			$filename = $this->dir . "view/" . $view . ".ctp";
	
			if(file_exists($filename)){
	
				$options = array(
					'view'=>$view
				);
	
				$content = file_get_contents($filename);
	
				$content = $this->smarty->smart($content, $data,$options);
	
				echo $content;
	
			}
	
			die();
	
		}


}
?>
