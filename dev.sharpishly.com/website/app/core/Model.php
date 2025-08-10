<?php

namespace App\Models;

use App\Core\Helper;
use App\Core\Db;
use dBug\dBug;
// use App\Models\Model;
use Attributes\Attributes;
use Partials\Partials;
use Form\Form;
use Data\Data;
use Deployment\Deployment;
use PurityControl\PurityControl;
use TimeWarp\TimeWarp;
use MigrationToFormArrayConverter\MigrationToFormArrayConverter;
use Exception;

class Model {

    public $helper;	
	public $db;
	public $attributes;
	public $partials;
	public $form;
	public $data;
	public $deployment;
	public $puritycontrol;
	public $timewarp;

	public function __construct(){

        $this->helper = new Helper();	
		$this->db = new Db();
		$this->attributes = new Attributes();	
		$this->partials = new Partials();   
        $this->form = new Form($this);
		$this->data = new Data();
		$this->deployment = new Deployment($this);
		$this->puritycontrol = new PurityControl();
		$this->timewarp = new TimeWarp();
	}

	public function set_link($data,$url,$template,$options=false){

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>'link',
		);
        
        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data, $template, $link);

		return $data;
	}


	// Convert Migration array to form field
	public function migration_to_form($data,$models,$options){

		if(isset($models['migrations'])){
			
			$method = 'migrate_products_orders';

			if(isset($options[__FUNCTION__])){

				$method = $options[__FUNCTION__];

			}

			$migration = $models['migrations']->$method;

			try {
	
				$rs = MigrationToFormArrayConverter::convert($migration);
	
				// new dBug($rs);
	
			} catch (Exception $e) {
	
				echo "Error: " . $e->getMessage();
	
			}
	
			$data = $this->fields($data,$rs,$options);
			
		}

		return $data;
	}

    /**
     * Get an element from a nested associative array.
     *
     * @param array $arr The input array, which can contain nested arrays.
     * @param string $key The key of the element to retrieve.
     * @return mixed The value associated with the given key, or null if not found.
     */
    public function getElementInArray($arr, $key)
    {
        foreach ($arr as $k => $value) {
            if ($k == $key) {
                return $value;
            } elseif (is_array($value)) {
                // If the value is an array itself, recursively search for the key
                $result = $this->getElementInArray($value, $key);
                if ($result !== null) {
                    return $result;
                }
            }
        }

        // If the key was not found in any nested arrays, return null
        return null;
    }

    public function set($data,$key,$val){
		
		$data[$key] = $val;
		
		return $data;
		
	}

	public function directive($data,$function){

		if(isset($data['directive']) && $data['directive'] === $function ){
			return TRUE;
		}

		return FALSE;

	}


	public function footer($data,$models=false,$options=false){

		$arr = array($data['model'],$data['directive']);

		$page = implode("/",$arr);

		$script = 'var app={};
		// path generated dynamically by PHP MVC 
		app.path="' . $_SERVER['HTTP_HOST'] . '";
		app.page="' . $page . '"';
		

		$arr = array(
			array(
				'title'=>'Sharpishly',
				'script'=>$script,
				'model'=>$data['model']
			)
		);

		$data = $this->partials->spartials($data,__FUNCTION__,$arr);

		return $data;
	}

	public function set_title_link($data,$models=false,$options=false){
		
		$url = $this->helper->url($data['model'] . '/index');

		$attr = array(
			'href'=>$url,
			'class'=>"navbar-brand"
		);

		return $this->attributes->set($attr);
	}

	public function header($data,$models=false,$options=false){

		$arr = array(
			array(
				'title'=>ucfirst(($data['title'])) . ' &#10084;',
				'add_todo'=>$this->helper->url('todo/create/1'),
				'title_link'=>$this->set_title_link($data),
				'admin'=>$this->helper->url('admin/index/1'),
				'settings'=>$this->helper->url('home/admin/1'),
				'model'=>$data['model']
			)
		);

		$data = $this->partials->spartials($data,__FUNCTION__,$arr);

		return $data;
	}

	public function header_set_links($data,$name,$page,$options=false){

		$url =  $this->helper->create_url($this->data->get($data,'model'),$page);

		if(isset($options['url']) && $options['url']){

			$url = $options['url'];

		}

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>__FUNCTION__,
		);
        
        $attr = $this->attributes->set($arr);
        
        $data['partials']['header_all'][0][$name] = $attr;

		return $data;
	}

	public function status($save,$data=false){

		$save['date'] = $this->timewarp->now();

		$save['created_at'] = $this->timewarp->now();
		
		$save['status'] = 1;
		
		return $save;
	}

	public function updates($data,$options){

		$tbl = 'migrate_todo';

		if(isset($options['tbl']) && !empty($options['tbl'])){

			$tbl = $options['tbl'];

		}

		$update = $_POST;

		$where = array(
			'id'=>$update['id']
		);

		unset($update['id']);

		$conditions = array(
			'table'=>$tbl,
			'update'=>$update,
			'where'=>$where
		);

		$rs = $this->db->update($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

	public function save($data,$options){

		$tbl = 'migrate_todo';

		if(isset($options['tbl']) && !empty($options['tbl'])){

			$tbl = $options['tbl'];

		}

		$save = $this->status($_POST);

		$save = $this->puritycontrol->safe_strings_for_db($save);

		$conditions = array(
			'table'=>$tbl,
			'save'=>$save
		);

		// new dBug($conditions);die();

		$rs = $this->db->save($conditions);

		$data = $this->set($data,__FUNCTION__,$rs);

		return $data;
	}

	public function fields($data,$rs,$options=false){
			
		$arr = array();

		foreach($rs as $key => $val){

			$attr = $this->attributes->set($val);

			$label_attr = array(
				"for"=>strtolower($key) . "-field",
				"class"=>"sr-only"
			);

			$label = $this->attributes->set($label_attr);

			$arr[] = array(
				'title'=>$key,
				'attr'=>$attr,
				'input'=>$attr,
				'label'=>$label
			);
		}

		$data = $this->partials->spartials($data,'fields',$arr);

		return $data;
	}

	public function create_form_from_partial($data,$models,$options){

		$restrict = array(
			'pref',
			'content',
			'date'
		);

		if(isset($options['restrict'])){

			$restrict = $options['restrict'];

		}

		$rs = $this->getElementInArray($data,'result')[0];

		$fields = array();

		foreach($rs as $key => $value){

			if(!in_array($key,$restrict)){

				if($key == 'id'){

					$fields[$key]['type'] = 'hidden';

				} else {

					$fields[$key]['type'] = 'text';

				}

				$fields[$key]['name'] = $key;

				$fields[$key]['id'] = $key;
	
				$fields[$key]['value'] = $value;

			}

		}
		
		$data = $this->fields($data,$fields);

		return $data;
	}

	public function check_if_records_exists($rs,$options){
		if(!isset($rs['result'][0]['id'])){

			$arr = array(
				array(
					'id'=>'',
					'title'=>'',
					'description'=>'',
					'url'=>'',
					'date'=>'',
					'price'=>'',
					'order_id'=>'',
					'product_id'=>'',
					'stock'=>'',
					'name'=>'',
					'biz_name'=>'',
					'quantity'=>'',
					'total'=>''
				)
			);

			$rs['result'] = $arr;

		}

		return $rs;
	}


	public function filter_records_by_id($data,$models,$options){

		$arr = array(
			'href'=>$this->helper->url($data['model'] . '/index/' . $data['start'] . '/' . $data['limit'] . '/ASC')
		);

		$attr = $this->attributes->set($arr);

		$data = $this->partials->template($data,__FUNCTION__,$attr);


		return $data;
	}

	public function pagination($data,$models=false,$options=false){

		if(empty($data['start'])){

			$data['start'] = 0;

		}

		$data = $this->set($data,__FUNCTION__,$data['start'] . ',' . $data['limit']);

		$start = (int)$data['start'] + (int)$data['limit'];//die();

		$arr = array(
			'href'=>$this->helper->url($data['model'] . '/index/' . $start . '/' . $data['limit'] . '/' . $data['sort'])
		);

		$attr = $this->attributes->set($arr);

		$data = $this->partials->template($data,'next',$attr);

		//@TOD0: Test
		if(empty($data['start'])){

			$arr = array(
				'onclick'=>'history.back();',
				'style'=>'display:none'
			);

		} else {

			$arr = array(
				'onclick'=>'history.back();',
				'style'=>'cursor:pointer;'
			);
		}


		$attr = $this->attributes->set($arr);

		$data = $this->partials->template($data,'previous',$attr);

		return $data;

	}
	/**
	 * @param: $rs;
	 */
	public function check_if_domain_exists($rs){

		if(isset($rs['result'][0]['url']) && !empty($rs['result'][0]['url'])){

			foreach($rs['result'] as $key => $value){

				$url = $value['url'];

				if($this->containsUrlDomain($url) != TRUE){

					$value['url'] = $this->helper->url($url);

				}

				$rs['result'][$key] = $value;

				//die();

			}

		}

		return $rs;

	}

	/**
	 * @param: String
	 */
	public function containsUrlDomain($string) {
		
		// Regular expression to match common URL patterns with domains.
		$urlPattern = '/\b(?:https?:\/\/|www\.)[a-z0-9.-]+\.[a-z]{2,}\b/i';

		return preg_match($urlPattern, $string) === 1;
	}

	public function quick_link($link){

		$url = $this->helper->url($link);

		$attr = array(
			'href'=>$url,
			'class'=>__FUNCTION__,
		);

		return $this->attributes->set($attr);
	}
	
}// end of Model

?>