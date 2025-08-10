<?php

namespace Form;
use dBug\dBug;

class Form {

    public $obj;
	
	public $statuses = array(
		'Active'=>'1',
		'Pending'=>'2',
		'Deleted'=>'3',
		'Completed'=>'4',
		'Progressing'=>'5',
		'Quarantine'=>'6'
	);


    public function __construct($obj){

        $this->obj = $obj;

    }

    public function set($data,$models=false,$options=false){
		

		$page = 'insert';

		if(isset($options['url']) && !empty($options['url'])){

			$page = $options['url'];

		}

        $url =  $this->obj->helper->create_url($this->obj->data->get($data,'model'),$page);

        $arr = array(
        	'action'=>$this->obj->helper->url($url),
        	'method'=>'POST',
		);

		if(isset($options['upload']) && !empty($options['upload'])){

			$arr['enctype'] ="multipart/form-data";
			
		}
        
        $form = $this->obj->attributes->set($arr);
        
        $data = $this->obj->partials->template($data, 'form', $form);
		
		return $data;

    }

	public function field($rs,$data){
    			
		foreach ($rs as $key => $value) {
			
	        $attr = array(
	        	'name'=>strtolower($value['title']),
	        	'id'=>strtolower($value['title']),
	        	'type'=>$value['type'],
	        	'placeholder'=>strtolower($value['description']),
	        	'class'=>'form-control',
	        	'aria-label'=>$value['title'],
	        	'aria-describedby'=>'basic-addon1'
			);

			if(isset($value['value']) && !empty($value['value'])){

				$attr['value'] = $value['value'];

			}
				        
	        $value['input'] = $this->obj->attributes->set($attr);
			
			
			 $rs[$key] = $value;			
		}

        return $rs;
    }

	public function fields($data,$rs,$models=false,$options=false){
			
		$rs = $this->field($rs, $data);
		
		$data = $this->obj->partials->spartials($data, __FUNCTION__, $rs);
		
		return $data;
	}

	public function status($arr,$opts,$config=false){
		
		foreach ($arr as $key => $value) {
						
			$opts = $this->options($opts, $key, $value,$config);
			
		}
		
		return $opts;		
		
	}
    
    public function options($select,$key,$val,$config=false){
        
        $select[$key] = "value='$val'";
		
		if(isset($config['selected']) && $config['selected'] == $val){
			
			$select[$key] .= " selected";
			
		}

        return $select;
        
    }
	
	public function select($attr="name='foo'",$options = array('foo'=>'value="bar"')){
		
		
		$option = '';
		
		foreach ($options as $key => $value) {
			
			
			$option .= "<option $value>$key</option>";
			
		}
		
		$select = "<select $attr>$option</select>";
		
		return $select;
		
	}


	public function statusToPartials($data=false){

		$rs = array();

		foreach($this->statuses as $key => $val ){

			if(isset($data['id']) && !empty($data['id'])){
				$url = $data['model'] . 
				'/changestatus/' . 
				$data['id'] . 
				'/' . 
				$val;		
			} else {
				$url = "";
			}

			$rs[] = array(
				'key'=>$key,
				'val'=>$val,
				'url'=>$this->obj->helper->url($url)
			);

		}

		return $rs;
	}

}
?>