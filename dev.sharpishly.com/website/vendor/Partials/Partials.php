<?php

namespace Partials;
use dBug\dBug;

class Partials {

	public function __construct() {

	}

	public function templates($data,$arr){
		
		foreach($arr as $key => $val){

			$data = $this->template($data,$key,$val);

		}
		
		return $data;
	}
	
	public function template($data,$key,$val){
		
		$data[__FUNCTION__][$key] = $val;
		
		return $data;
	}
	
	public function spartials($data,$key,$value){

		$el = 'partials';

		$p = $key . '_all';

		$data[$el][$key]['sub-partial'] = $p;

		$data[$el][$p] = $value;

		return $data;
	}
	
	public function start($data,$key,$value){

		$el = 'partials';

		$p = $key . '_all';

		$data[$el][$key]['sub-partial'] = $p;

		return $data;
	}
    
    public function part($data,$key=false,$value=false){

        $data['partials'][$key . '_all'][] = $value;

        return $data;
    }

}
?>