<?php

namespace App\Models;

use dBug\dBug;

use SyncFile\SyncFile;

class UrbanwetsuitModel extends Model {

    public function main($data,$models){

		//$this->syncfile = new SyncFile();

        $options = [];

		$data = $this->set($data,'tbl','migrate_interviews');

		$data = $this->set($data,'tbl_notes','migrate_interviews_notes');

		$data = $this->header($data);

		$data = $this->set_header_link($data,$models,$options);
			
		$data = $this->footer($data);

        $data = $this->index($data,$models,$options);

		$data = $this->update($data,$models,$options);

		$data = $this->create($data,$models,$options);

		$data = $this->modify($data,$models,$options);

		$data = $this->add($data,$models,$options);

		$data = $this->details($data,$models,$options);

		$data = $this->notes($data,$models,$options);

		$data = $this->add_note($data,$models,$options);

		$data = $this->login($data,$models,$options);

		$data = $this->gateway($data,$models,$options);

		$data = $this->cv($data,$models,$options);

        // new dBug($data);

		// new dBug($_SESSION);

        // die();

        return $data;
    }

	public function set_header_link($data,$models,$options){

		$fields = array(
			'add'=>'create',
			'login'=>'login',
			'records'=>'index'

		);

		foreach($fields as $key => $value){

			$data = $this->header_set_links($data,$key,$value,$options);

		}

		return $data;
	}

	public function gateway($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			if($_POST['email'] == "steve@austin.com" && $_POST['password'] == "admin12345"){

				$_SESSION['user'] = $_POST['email'];

				$title = "Welcome user " . $_POST['email'];

				$msg = "Access granted";

			} else {

				// Print error message
				$title = "Incorrect credentials provided " . $_POST['email'];

				$msg = "Access refused";

			}

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', $msg);
			
		}
		
		return $data;
		
	}

	public function login($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = 'Login';

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Please ' . $title);
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->login_with_google($data,$models,$options);

			$data = $this->header_set_links($data,'funding-records','records');

			$options['url'] = 'gateway';

			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Email'=>array(
					'name'=>'email',
					'placeholder'=>'Please enter your email',
					'type'=>'text',
					'required'=>'required'
				),
				'Password'=>array(
					'name'=>'password',
					'placeholder'=>'Provide your email',
					'type'=>'password',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			
		}
		
		return $data;
		
	}

	public function login_with_google($data,$models,$options){

        $url =  'google/auth';

        $arr = array(
        	'href'=>$this->helper->url($url),
        	'class'=>'link',
		);
        
        $link = $this->attributes->set($arr);

		$data = $this->partials->template($data, __FUNCTION__, $link);

		return $data;
	}

	public function add_note($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['tbl'] = $data['tbl_notes'];
			
			$data = $this->save($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title','::Hub::');

			$data = $this->partials->template($data,'home',$this->helper->url($data['model'] . "/details/" . $data['id']));
			
		}
		
		return $data;
		
	}

	public function notes($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['url'] = 'add_note/' . $data['id'];
			
			$data = $this->form->set($data,$models,$options);

			$rs = array(
				'Title'=>array(
					'name'=>'title',
					'placeholder'=>'What is the name?',
					'type'=>'text',
					'required'=>'required'
				),
				'Description'=>array(
					'name'=>'description',
					'placeholder'=>'Provide a description?',
					'type'=>'text',
					'required'=>'required'
				)
			);

			$data = $this->fields($data,$rs,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'note_id',$data['id']);

			$data = $this->partials->template($data,'h2','Provide an description of ' . $data['title']);

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}

	public function details($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$data = $this->get_note_by_id($data,$models,$options);

			$wheres = array(
				'id'=>$data['id']
			);
			
			$conditions = array(
				'table'=>$data['tbl'],
				'order'=>array('id'=>'DESC'),
				'wheres'=>$wheres
			);

			$rs = $this->db->find($conditions);

			$rs = $this->check_if_records_exists($rs,$options);

			$rs = $this->set_record_url($rs,'link','update',$data);
				
			$data = $this->partials->spartials($data,'records',$rs['result']);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update ' . ucfirst($data['title']));

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');
			
		}
		
		return $data;
		
	}


	public function add($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){
			
			$save = $this->status($_POST);

			$save = $this->puritycontrol->safe_strings_for_db($save);

			$conditions = array(
				'table'=>$data['tbl'],
				'save'=>$save
			);
	
			$rs = $this->db->save($conditions);

			$data = $this->set($data,'save',$rs);

			$data = $this->set($data,'conditions',$conditions);

			$title = "Add funding source";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');

			$url = $this->helper->url($data['model'] . "/index");

			$attr = array(
				'href'=>$url,
				'id'=>'link'
			);

			$link = $this->attributes->set($attr);
                        
            $data = $this->partials->template($data, 'url', $link);
			
		}
		
		return $data;
		
	}

	public function modify($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['tbl'] = $data['tbl'];
			
			$data = $this->updates($data,$options);

			$data = $this->partials->template($data,'h1','Add Task');

			$data = $this->partials->template($data,'h2','Provide an description of todo');

			$data = $this->partials->template($data,'title','::Hub::');

			$url =$this->helper->url($data['model'] . "/details/" . $_POST['id']);

			$data = $this->partials->template($data,'home',$url);
			
		}
		
		return $data;
		
	}

	public function create($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$title = "Add funding source";

			$data = $this->partials->template($data, 'h1', $title);
            
            $data = $this->partials->template($data, 'h2', 'Add description');
                        
            $data = $this->partials->template($data, 'title', $title);

			$data = $this->header_set_links($data,'funding-records','records');

			$options['url'] = 'add';

			$data = $this->form->set($data,$models,$options);

			$options['migration_to_form'] = $data['tbl'];

			$data = $this->migration_to_form($data,$models,$options);			
		}
		
		return $data;
		
	}

	public function status_to_partials($data,$models=false,$options=false){

		$priority = $this->form->statusToPartials($data);

		$priority = $this->set_selected_status($priority,$data);

		$data = $this->partials->spartials($data,'selector',$priority);

		return $data;
	}

	public function set_selected_status($priority,$data){

		$id = $data['database_records']['result'][0]['status'];

		foreach($priority as $key => $value){

			$arr = array(
				'value'=>$value['val']
			);

			if($id == $value['val']){

				$arr['selected'] = 'selected';

			}

			$attr = $this->attributes->set($arr);

			$value['attr'] = $attr;

			// new dBug($value);

			$priority[$key] = $value;
		}

		// new dBug($priority);

		return $priority;
	}

	public function update($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['restrict'] = array('content','date');

			$data = $this->get_record_by_id($data,$models,$options);

			$data = $this->create_form_from_partial($data,$models,$options);

			$data = $this->status_to_partials($data);

			//@TODO temp unset
			unset($data['partials']['fields_all'][7]);

			$data = $this->set($data, 'title', $data['title']);

			$data = $this->partials->template($data,'h1','Update ' . $data['model']);

			$data = $this->partials->template($data,'h2','Change required information');

			$data = $this->partials->template($data,'title','::Hub::');

			$options['url'] = 'modify';

			$data = $this->form->set($data,$models,$options);

			$data = $this->partials->template($data,'add',$this->helper->url($data['model'] . "/notes/" . $data['id']));

			$data = $this->partials->template($data,'details',$this->helper->url($data['model'] . "/details/" . $data['id']));
		}
		
		return $data;
		
	}

	public function get_record_by_id($data,$models,$options){

		$wheres = array(
			'id'=>$data['id']
		);
		
		$conditions = array(
			'table'=>$data['tbl'],
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		$data = $this->set($data,'database_records',$rs);

		$rs = $this->set_record_url($rs,'foo','bar',$data);

		$data = $this->partials->spartials($data,'records',$rs['result']);

		return $data;
	}

	public function get_note_by_id($data,$models,$options){

		$wheres = array(
			'noteid'=>$data['id']
		);
		
		$conditions = array(
			'table'=>$data['tbl_notes'],
			//'order'=>array('id'=>'DESC'),
			'wheres'=>$wheres
		);

		$rs = $this->db->find($conditions);

		$rs = $this->check_if_records_exists($rs,$options);

		//$rs = $this->decodeHtmlEntities($rs,$data);

		$data = $this->partials->spartials($data,'notes',$rs['result']);

		return $data;
	}

	public function upload($data,$models=false,$options=false){

		// Define your upload configuration
		$uploadDir = $this->helper->dir . 'uploads/'; // Create an 'uploads' directory next to your cv.php script
		$maxFileSize = 5 * 1024 * 1024; // 5 MB
		$allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

		// Instantiate the SyncFile class
		$fileHandler = new SyncFile($uploadDir, $maxFileSize, $allowedTypes);

		if ($fileHandler->handleUpload('myFile')) { // 'myFile' should match the name attribute of your <input type="file">
			$uploadedInfo = $fileHandler->getUploadedFileInfo();
			error_log("APP DEBUG: File upload SUCCESS! Details: " . print_r($uploadedInfo, true));
			// Handle success (e.g., save to database, redirect)
		} else {
			$errors = $fileHandler->getErrors();
			error_log("APP DEBUG: File upload FAILED. Errors: " . print_r($errors, true));
			// Handle errors (e.g., display error messages to user)
		}

		return $data;
	}

	public function resume($data,$fields,$options=false){

		$partial = array();

		foreach($fields as $key => $value){

			$url = $this->helper->url($value);

			$arr = array(
				'href'=>$url,
				'class'=>'work'
			);

			$attr = $this->attributes->set($arr);

			$part = array(
				'title'=>$key,
				'attr'=>$attr
			);

			$partial[] = $part;

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$partial);

		return $data;
	}

    public function cv($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$fields = array(
				'Upload another cv'=>'syncapply/index',
				'View CV'=>'syncapply/profile',
			);

			$data = $this->resume($data,$fields);

			$data = $this->slideshow($data);

			$data = $this->upload($data);

			$arr = array(
				'h1'=>'Upload Successful',
				'h2'=>'The one job application that rules them all',
				'title'=>'Upload Successful'
			);

			$data = $this->partials->templates($data,$arr);
			
		}

		return $data;

	}

    public function index($data,$models,$options){
		
		if($this->directive($data,__FUNCTION__)){

			$options['url'] = 'cv';

			$options['upload'] = TRUE;

			$data = $this->form->set($data,$models,$options);

			$fields = array(
				'Whats the catch?'=>'urbanwetsuit/index',
				//'Too good to be true?'=>'syncapply/index',
			);

			$data = $this->work($data,$fields);

			$data = $this->slideshow($data);

			$h2 = 'Foo bar &copy;';

			$arr = array(
				'h1'=>'Urban Wetsuit &trade;',
				'h2'=>$h2,
				'title'=>'Urban Wetsuit &trade;'
			);

			$data = $this->partials->templates($data,$arr);
			
		}

		return $data;

	}


	public function work($data,$fields,$options=false){

		$partial = array();

		foreach($fields as $key => $value){

			$url = $this->helper->url($value);

			$arr = array(
				'href'=>$url,
				'class'=>'work'
			);

			$attr = $this->attributes->set($arr);

			$part = array(
				'title'=>$key,
				'attr'=>$attr
			);

			$partial[] = $part;

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$partial);

		return $data;
	}

	public function slideshow_source($attr,$i){

		$arr = array(
			'srcset'=>"/urbanwetsuit/images/toy_0" . $i . ".webp",
			'type'=>"image/webp"
		);

		$attr['source'] = $this->attributes->set($arr);

		return $attr;
	}

	public function slideshow_img($attr,$i){

		$arr = array(
			'src'=>"/syncapply/images/toy_0" . $i . ".webp",
			'alt'=>"Retro futuristic toy " . $i
		);

		$attr['img'] = $this->attributes->set($arr);

		return $attr;
	}

	public function slideshow_carousel($attr,$i){

		$arr = array();

		if($i === 1){

			$arr['class']="carousel-slide active";

		} else {

			$arr['class']="carousel-slide";

		}

		$attr['carousel'] = $this->attributes->set($arr);

		return $attr;
	}

	public function slideshow($data,$models=false,$options=false){

		$max = 5;

		$partial = array();

		for($i=1; $i < $max; $i++){

			$attr = array();

			$attr = $this->slideshow_source($attr,$i);

			$attr = $this->slideshow_img($attr,$i);

			$attr = $this->slideshow_carousel($attr,$i);

			$partial[] = $attr;

		}

		$data = $this->partials->spartials($data,__FUNCTION__,$partial);

		return $data;
	}


	public function set_external_url($rs,$partial){

		foreach($rs['result'] as $key => $value){


			$attr = array(
				'href'=>$value['url'],
				'class'=>'_link',
				'target'=>'_blank'
			);

			$link = $this->attributes->set($attr);

			$value[$partial] = $link;

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

	public function set_record_url($rs,$partial,$name,$data){

		foreach($rs['result'] as $key => $value){

			$url = $this->helper->url($data['model'] . '/' . $name . '/' . $value['id']);

			$attr = array(
				'href'=>$url,
				'class'=>'_link'
			);

			$link = $this->attributes->set($attr);

			$value[$partial] = $link;

			$rs['result'][$key] = $value;

		}

		return $rs;

	}

}

?>