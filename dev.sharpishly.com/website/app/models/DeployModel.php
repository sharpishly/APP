<?php

namespace App\Models;

use dBug\dBug;

class DeployModel extends Model {

    public function main($data,$models){

        $options = [];

		$data = $this->header($data);

		$data = $this->footer($data);

        $data = $this->index($data,$models,$options);

        new dBug($data);

        return $data;
    }

    public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'index'){

			$deploy = $this->deployment->run('192.168.0.22','joe90','M0neyplus');

			$data = $this->set($data, 'response', $deploy);
			
			$conditions = array(
				'table'=>'migrate_personal_details',
				'order'=>array('id'=>'DESC'),
				'limit'=>'0,5'
			);
			
			$rs = $this->db->find($conditions);
			
			$data = $this->set($data, 'records', $rs);
			
			$data = $this->set($data, 'title', $data['title']);

			
		}
		
		return $data;
		
	}

}

?>