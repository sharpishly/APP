<?php

namespace Data;
use dBug\dBug;

class Data {

	public function __construct() {

	}
	
	public function find($arr,$key){
		
		$it = new  \RecursiveIteratorIterator(new \RecursiveArrayIterator($arr, \RecursiveArrayIterator::CHILD_ARRAYS_ONLY));
		
		$it = new \CachingIterator($it,0);
		
		foreach ($it as $e) {
			
			if($it->key() == $key){
				
				return $it->current();
				
			}
			
		}
			
		return FALSE;
	}

	public function get($data, $key) {

		if (isset($data[$key])) {

			return $data[$key];

		} else {

			return FALSE;

		}

	}

	public function set($data, $key, $val) {

		$data[$key] = $val;

		return $data;

	}

	public function convertParagraphs($content) {
		$paragraphs = explode("\r\n", $content);
		$convertedContent = '';

		foreach ($paragraphs as $paragraph) {
			if (!empty($paragraph)) {
				$convertedContent .= "<p>$paragraph</p>";
			}
		}

		return $convertedContent;
	}
	
	public function save_for_conditions($tbl){

        $save = array(
            'table'=>$tbl,
            'save'=>$_POST
        );

		$save['save']['date'] = date('Y-m-d h:m:s');
		
		$save['save']['status'] = 1;
		
		return $save;
		
	}

}
?>