<?php

namespace Flash;

class Flash {
    
    public $partials;
    
    public $attributes;
	
	public $helper;
    
    public function __construct($partials,$attributes,$helper){
        
        $this->partials = $partials;
        
        $this->attributes = $attributes;
		
		$this->helper = $helper;
        
    }
    
    public function start($data,$msg=false,$title=false,$url=false){
        
        $data = $this->partials->spartials($data,'flash',$this->partial($msg,$title,$url));
        
        return $data;
    }
    
    public function partial($msg,$title,$url){

        $attributes = array('class'=>"alert alert-primary", "role"=>"alert");
        
        $attr = $this->attributes->set($attributes);
		
		$anchor = array(
			'href'=>$this->helper->url($url),
			'class'=>"alert-link"
		);
        
        $flash = array(
            'msg'=>$msg,
            'link'=>$title,
            'attr'=>$attr,
            'anchor'=>$this->attributes->set($anchor)
        );
        
        return array($flash);
    }
    /**
	 * @param $data, $msg, $link
	 * */
    public function alert($data,$msg=false,$link=false){
        
        $attributes = array(
            'class'=>"alert alert-success", 
            "role"=>"alert"
        );
        
        $attr = $this->attributes->set($attributes);
        
        $flash = array('msg'=>$msg,'link'=>$link,'attr'=>$attr);
        
        $data = $this->partials->part($data,'flash',$flash);
        
        return $data;
    }
 
     /**
	 * @param $data, $msg, $link
	 * */   
    public function danger($data,$msg=false,$link=false){
        
        $attributes = array(
            'class'=>"alert alert-danger", 
            "role"=>"alert"
        );
        
        $attr = $this->attributes->set($attributes);
        
        $flash = array('msg'=>$msg,'link'=>$link,'attr'=>$attr);
        
        $data = $this->partials->part($data,'flash',$flash);
        
        return $data;
    }
}

?>