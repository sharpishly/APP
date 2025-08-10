<?php

namespace App\Models;

use dBug\dBug;

class PhdModel extends Model {


    public function main($data,$models){

        $options = [];

        $data = $this->index($data,$models,$options);

        new dBug($data);

        die();

        return $data;
    }

    public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'index'){
			
			$conditions = array(
				'table'=>'migrate_phd',
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