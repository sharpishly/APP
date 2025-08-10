<?php

namespace Js;

use dBug\dBug;

class Js {
	
	public $dir;
	
	public $contents;
    
    public function __construct(){
    			
		$this->dir = $_SERVER['DOCUMENT_ROOT'] . '/js/';
        
    }
	
	public function load($path,$data=false){
		
		$file = $this->dir . $path;
		
		if(file_exists($file)){
			
			$contents = file_get_contents($file);
			
			$contents = $this->smarty($contents, $data);
			
			$this->contents .= $contents;
			
		}			
		
	}
	
	public function smarty($contents,$data){
		
		if(is_array($data)){
			
			$contents = str_replace("['data']", json_encode($data), $contents);
			
		}
		
		return $contents;
		
	}
	
	public function render(){
		
		return $this->contents;
		
	}
}

?>