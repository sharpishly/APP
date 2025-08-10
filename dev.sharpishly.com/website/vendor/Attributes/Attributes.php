<?php

namespace Attributes;

class Attributes {
	
	public function get($attr,$options=false){
		
		$rs = explode(' ',$attr);
		
		$counter = 0;
		
		$res = array();
		
		while($counter<count($rs)){
			
			$m = $rs[$counter];
			
			$arr = explode('=', $m);
			
			if(isset($arr[1])){
				
				$v = str_replace("'", '', $arr[1]);
				
				if(!empty($v)){
					
					$res[$arr[0]] = $v;
					
				}
				
				
				
			}
			
			
						
			$counter++;
			
		}
				
		return $res;
	}
	
	public function set($attr,$options=false){

		$it = new \ArrayIterator($attr);

		$it = new \CachingIterator($it);

		$result = '';

		foreach ($it as $ele) {

			if($it->hasNext()){

				$result .= $it->key() . "='" . $it->current() . "' ";

			} else {

				//@TODO: Fix or simplify
				$result .= $it->key() . "='" . $it->current() . "' ";

			}

		}

		return $result;

	}	
	
}
?>