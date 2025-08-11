<?php

namespace App\Core;

use dBug\dBug;

class App {
	
	// protected $controller = 'home';
	protected $controller = 'browser';
	
	// protected $method = 'index';
	protected $method = 'index';
	
	protected $params;
	
	public function __construct($conf){
		
		
		$this->loader($conf, "/app/core/Db.php");

		$this->loader($conf, "/app/core/Helper.php");

		$this->loader($conf, "/app/core/Model.php");

		$url = explode('/',$_SERVER['REQUEST_URI']);

		$this->control($conf);

		$url = $this->set_sub_domain_routes($url);

		$url = $this->googleapis($url);
				
		$this->controller($url,$conf);
		
		$this->home($url, $conf);
		
		$this->page_does_not_exist($conf,$url);
				
		unset($url[0],$url[1],$url[2]);
		
		$this->params = $url;

		call_user_func_array(array($this->controller,$this->method),$this->params);
		
	}

	public function set_sub_domain_routes($url){

		//@TODO: Remember to update env.php file with matching credentials

		// new dBug($url);

		$excluded = array(
			'migrations',
			'admin',
			'seed',
			'google',
			'facebook',
			'hotmail.com',
			'surveyor',
			'dashboard',
			'syncapply',
			'sharpishly',
			'todo',
			'vacancies',
			'urbanwetsuit',
			'email',
			'fxsurveyor',
			'excusegame',
			'yikesdude',
			'yougogirl',
			'headers',
			'api'
		);

		$fields = array(
			'test.sharpishly.com'=>'browser',
			'admin.sharpishly.com'=>'admin',
		);

		$action = array(
			'sharpishly'=>array('start')
		);
		
		$host = $_SERVER['HTTP_HOST'];

		if(isset($fields[$host])){

			$route = $fields[$host];

			if(in_array($url[1],$excluded)){

				// Do nowt... for now...

			} else {

				// $url[1] contains controller method
				$url[1] = $route;
				
			}
						
			if(isset($action[$route]) && !isset($url[2])){

				$url[2] = $action[$route][0];

				// new dBug($url);

			}

		}

		return $url;
	}

	public function googleapis($url){

		parse_str($_SERVER['REQUEST_URI'],$params);
		
		foreach($params as $key => $value ){

			$key = str_replace('/?','',$key);

			$params[$key] = $value;


		}

		if(isset($params['code'])){

			$arr = array(
				'',
				'google',
				'callback',
				$params['code'],
				$params['scope']
			);

			return $arr;
		}
		

		return $url;
	}

	public function page_does_not_exist($conf,$url){

		if(isset($url[1]) && !empty($url[1])){

			$controller = $conf['dir'] . '/app/controllers/' . $url[1] . '.php';

			if(!file_exists($controller)){

				print_r(array('file does not exist'=>$controller));

				$controller = $conf['dir'] . '/app/controllers/' . $this->controller . '.php';
			
				require_once $controller;
				
				$this->controller = 'App\\Controllers\\' . $this->controller;
				
				$this->controller = new $this->controller;	

			}

		}

	}
	
	public function loader($conf,$path){

		$file = $conf['dir'] . $path;//die();
		
		require_once $file;
		
	}
	
	public function control($conf){

		$controller = $conf['dir'] . '/app/core/Controller.php';
		
		require_once $controller;
		
	}
	
	public function home($url,$conf){
		
		if(empty($url[1])){
			
			$controller = $conf['dir'] . '/app/controllers/' . $this->controller . '.php';
			
			require_once $controller;
			
			$this->controller = 'App\\Controllers\\' . $this->controller;
			
			$this->controller = new $this->controller;			
		}	
		
	}
	
	public function controller($url,$conf){
		
		if(isset($url[1]) && !empty($url[1])){
			
			$controller = $conf['dir'] . '/app/controllers/' . $url[1] . '.php';
			
			if(file_exists($controller)){
				
				require_once $controller;
				
				$this->controller = 'App\\Controllers\\' . $url[1];
				
				$this->controller = new $this->controller;
				
				if(!empty($url[2])){

					$this->method = $url[2];

				}
							
			}
		
			
		}		
	}
	
}

