<?php

namespace App\Controllers;
use App\Core\Controller;
use dBug\dBug;

class Deploy {

    public function index($id=''){

        $data = array(
			'id'=>$id,
			'directive'=>__FUNCTION__,
			'title'=>'deploy'
		);

        $cont = new Controller();
		
		$models = array(
			$data['title']=>'DeployModel'
		);
		
		$models = $cont->model($models);

		$data = $models[$data['title']]->main($data,$models);

		$cont -> views( $data['title'] . '/' . __FUNCTION__, $data);		

    }
}

?>