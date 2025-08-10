<?php

namespace PurityControl;
use dBug\dBug;

class PurityControl {

    public function __construct()
    {
        
    }

    public function make_safe($value){

        $value = htmlentities($value);

        $value = htmlspecialchars($value);

        $value = addslashes($value);

        $value = ltrim($value);

        $value = trim($value);

        $value = rtrim($value);

        return $value;
    }

    public function safe_strings_for_db($save){
        
        foreach($save as $key => $value){

            $save[$key] = $this->make_safe($value);

        }

        return $save;

    }

    public function decodeHtmlEntities($encodedString) {

        return html_entity_decode($encodedString, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        
    }

}

?>