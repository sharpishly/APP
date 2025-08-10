<?php

namespace App\Models;

use App\Core\Helper;
use App\Core\Db;
use dBug\dBug;
use App\Models\Model;
use Attributes\Attributes;
use Partials\Partials;
use Form\Form;
use Data\Data;
use App\Core\FormReader;

class ReaderModel extends Model{

	public $helper;	
	public $db;
	public $attributes;
	public $partials;
	public $form;
	public $data;

    public function main($data,$models,$options=false){
		
		$data = $this->index($data, $models, $options);

		return $data;
	}

    public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

			// Instantiate the FormReader and read the form
			$formReader = new FormReader($this->html());

			$formValues = $formReader->readForm();

			$data = $this->set($data,'form_values',$formValues);

            new dBug($data);

            die();

        }
    }

	public function html(){
		// Example HTML content with a form
		$htmlContent = <<<HTML
		<!DOCTYPE html>
		<html>
		<body>
			<form action="/submit">
				<input type="text" name="username">
				<input type="password" name="password">
				<input type="confirm_password" name="confirm_password">
				<input type="email" name="email">
				<input type="radio" name="gender" value="male">
				<input type="radio" name="gender" value="female">
				<input type="checkbox" name="subscribe" value="yes">
				<select name="country">
					<option value="US">United States</option>
					<option value="CA">Canada</option>
				</select>
				<textarea name="comments"></textarea>
				<input type="submit" value="Submit">
			</form>
		</body>
		</html>
		HTML;

		return $htmlContent;
	}


}

?>