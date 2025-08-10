<?php

namespace App\Controllers;
use App\Core\Controller;
use dBug\dBug;

class Phd {

    public function index($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>'phd'
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'PhdModel'
		);
		
		$models = $cont->model($models);
		$data = $models[$data['title']]->main($data,$models);
    }
}

?>