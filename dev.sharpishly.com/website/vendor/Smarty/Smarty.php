<?php

namespace Smarty;

use dBug\dBug;


class Smarty {

	public $partial_directory;
	
	public $conf;
	
	public $dir;
	
	public function __construct($p){
			
		$this->conf = str_replace('public', 'app/', $_SERVER['DOCUMENT_ROOT']);
		
	}

	public function smart($content,$data,$options=false){

		$this->partial_path($options);

		$data = $this->partials($content, $data);

		$content = $this->template($content, $data);
				
		return $content;

	}

	public function partial_path($options){

		$arr = explode("/", $options['view']);

		$this->partial_directory = $arr[0];

	}

	public function bind($data,$key){

		if(isset($data['partials'][$key . "_all"])){

			$rs = $data['partials'][$key . "_all"];

			$counter = 0;

			while($counter<count($rs)){

				$data = $this->bind_partial($rs[$counter],$data,$key);

				$counter++;

			}

		}

		return $data;
	}

	public function presmarty($data,$key){

		$el = 'smarty';

		if(!isset($data[$el][$key])){

			$data[$el][$key] = '';

		}

		return $data;
	}
	
	public function isAssoc(array $arr)
	{
		if (array() === $arr) return false;
		
		return array_keys($arr) !== range(0, count($arr) - 1);
		
	}

	public function typecast($r){

		if(gettype($r) != 'string'){
			
			if(is_array($r)){

				if($this->isAssoc($r)){
					
					$res = array();
					
					foreach($r as $k => $v){
						
						$res[] = $k;
						
						$res[] = $v;
						
						$r = $res;
					}
									
				}
				
				//TODO: Temp fix
				//return implode('-',$r);
				
				return "foo-bar";
			}
			

		}

		return $r;
	}

	public function bind_partial($partial,$data,$key){

		$content = $data['template'][$key];

		foreach ($partial as $rs => $r) {

			$r = $this->typecast($r);

			$content = str_replace('{{{' . $rs .'}}}', $r, $content);

		}

		$data = $this->presmarty($data,$key);

		$data['smarty'][$key] .= $content;

		return $data;
	}

	public function partials($content,$data){

		$data = $this->get_partials($content, $data);

		return $data;

	}


	public function get_partials($content,$data){

		if(isset($data['partials'])){

			foreach ($data['partials'] as $key => $value) {

				$data = $this->detect($data,$key, $value);

			}

		}

		return $data;

	}

	public function detects($value,$el){

		if(isset($value[$el]) && !empty($value[$el])){

			return $value[$el];

		}

		return false;

	}

	public function detect($data,$key,$value){

		$sub = $this->detects($value,'sub-partial');

		$p = $this->detects($data,'partials');

		if($sub != false && $p != false){

			if(isset($p[$sub])){

				$filename = $this->conf . "view/" .  $this->partial_directory . "/partials/" . $sub . ".ctp";

				$partial = "";

				if(file_exists($filename)){

					$partial = file_get_contents($filename);

				}


				$data['template'][ $key]=$partial;

			}

		}

		return $data;

	}


	public function template($content,$data){

		if(isset($data['template'])){

			foreach ($data['template'] as $key => $value) {

				$data = $this->bind($data, $key);

				if(isset($data['smarty'][$key])){

					$value = $data['smarty'][$key];

				}

				$content = str_replace("{{{" . $key ."}}}", $value, $content);

			}

		}

		return $content;

	}

}

?>
