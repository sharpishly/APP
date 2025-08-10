<?php

namespace App\Controllers;

use App\Core\Controller;

use dBug\dBug;

class Reader {

    public function index ($id = '',$no = ''){
		
		$data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'no'=>$no,
			'title'=>'reader'
		);
				
		$cont = new Controller();
		
		$models = array(
			'reader'=>'ReaderModel'
		);
		
		$models = $cont->model($models);
		
		$data = $models['reader']->main($data,$models);		
				
		
	}

}

?>