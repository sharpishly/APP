### READ ME ###

## Todo ##
* Create new repo by copying this repo delete all .git files then init folder

## Install ##

# Hosts
* http://domain/deploy/index

# Databases
* http://domain/init/index

# Test pages to play around with
* http://domain/test/index

# Shared assets 
* http://domain/shared/index

# Imap
* sudo apt-get install php7.0-imap

# Whitelist Ips
* MSN JMRP https://sendersupport.olc.protection.outlook.com/snds/data.aspx

* MSN SNDS https://sendersupport.olc.protection.outlook.com/snds/

* MSN Support https://support.microsoft.com/en-us/supportrequestform/8ad563e3-288e-2a61-8122-3ba03d6b8d75

# bake

```
sudo ./bake.sh settings Settings

```

# Return specific fields in records

```
		$conditions = array(
			'table'=>'migrate_project_details',
			'wheres'=>$wheres,
			'field_names'=>array('name','description')
		);
```

## Sandbox db set in Db class
```
	public function sandbox() {

		if ($_SERVER['HTTP_HOST'] === 'sandbox.typekoce.com') {

			//$this -> result['password'] = 'r00taccess';
			
			$this -> result['password'] = '';

		}//die();

	}

```

# Note #
* The route paramater in $data['slug'] may cause issues with array variable. 

## Sub Modules ##
*** Note clone into build & remove .git folders & move to lib ***

** Office 365
* https://github.com/Typekoce/Groups-API-Office-Add-in-PHP-Sample.git

** Facebook
** Note not using submodules
* git submodule add https://github.com/facebook/php-graph-sdk.git /app/lib/facebook

** Facebook call back
* http://domain/signin/update

## Admin Mode ##
```
	public function admin_before($data,$options){

		$id = array('bereavement','id');
		
		$userid = $this->session->read($id);
		
		if($userid == 1){
			
			$data = $this->admin_before_flash($data, $options);
			
		} else {
			
			
		}
		
		return $data;
	}

	public function admin_before_flash($data,$options){
		
		$id = $data['id'];

		$url = array(
			$data['slug']['route']['model'],
			'read',
			$id
		);
		
		
		$url = $this->surl($url);
		
		$options = $this -> options($options, 'protocol', 'HTTPS');
		
		$url = $this->html->link($url,$options);
		
		$attr = array(
			'href'=>$url,
			//'target'=>'_blank'
		);
		
		$attr = $this->attributes($attr);
		
		$options = $this->options($options, 'attr', $attr);
		
		$txt = 'You are in administrator mode';
		
		$msg = $this->html->hyper($txt,$options);
		
		$flash = 'info';
						
		$data = $this->flash($data,$msg,$flash);

		
		return $data;
	}
```


## Git Remove submodule ##
```

rm -R path/to/submodule

Edit: 
./git/config
.gitmodules

```

## JsMVC setup ##

** Loading $data via JavaScript
```
	$data = $this->otherworld($data, $options);

	$options = $this->options($options, 'dynabase', '/theme/businessplan/js/');
	
	$data = $this->smodelfn($data,$models,'home','dynamic',$options);
	
				
	$options = $this->options($options, 'dynaroute', array('app'));
	
	$data = $this->smodelfn($data,$models,'home','dynamics',$options);


```
## JsMVC pass attional data ##
```
	public function otherworld($data,$options){
		
		$datas = array();
		
		$datas = $this->sdata($datas, 'route', 'simple');
		
		$data = $this->sdata($data, __FUNCTION__, $datas);
		
		return $data;
	}
```


* Controller

```
var cont = {};

cont = app.control('dashboard',cont);

app.controller(cont);

model = app.model('dashboard',model);

```

* Model 
```
model.max = 20;

model.selects = [];

model.main = function(){

};
```
* App
```
var app = {};

app.data = JSON.parse('mdata');

app.model = function(name,model){
	
	model.model = name;
	
	model.main();
	
	return model;
};

app.control = function(name,cont){
	
	cont.directive = name;
	
	return cont;
};

app.controller = function(con){
		
};

app.main = function() {

	prettyBug(app);
		
};

window.onload = function() {

	app.main();
	
};
```

## Thead
```
	public function thead($data, $options) {

		$rs = array( 
			array('title' => 'Type', 'icon' => 'file-text'),
			array('title' => 'Title', 'icon' => 'file-text'),
			array('title' => 'Date', 'icon' => 'file-text'), 
		);

		$data = $this -> spartial($data, __FUNCTION__, $rs);

		return $data;
	}
```

## Conditions passed to $data array

** Load method via model call

```

	$options = $this->options($options, 'conditions', __FUNCTION__);
	
	$data = $this->conditions($conditions, $data, $options);
	
	$data = $models['picturebox']->conditions($conditions, $data, $options);

```


## GitHub setup

* Change from HTTP to SSH
```
git remote set-url origin git@github.com:<Username>/<Project>.git
```

## Generic Request Object ##
```

* Initial abstraction for making PUT, POST, GET, ETC Requests

OpengraphModel->chew($data,$options);

```



## Mysql reset priveleges ##

* If Error 2003 try changing from localhost to 127.0.0.1

```
$ sudo mysql -u root # I had to use "sudo" since is new installation

mysql> USE mysql;
mysql> UPDATE user SET plugin='mysql_native_password' WHERE User='root';
mysql> FLUSH PRIVILEGES;
mysql> exit;

$ service mysql restart
```

## Nginx Set-Up ##
```
server {

	listen 80;
	
	root /var/www/typekoce/public/;
	
	index index.html index.php;
	
	server_name sandbox.typekoce.com; 

    # conditional rewrite for zee pretty URLS
    if (!-e $request_filename) {
        	rewrite ^/(.+)$ /index.php?url=$1 last;
        	break;
	}

    # Serve cakePHP files
    location ~ \.php$ {
	# With php5-fpm:
	## UPDATE PHP VERSION##
	fastcgi_pass unix:/run/php/php7.2-fpm.sock;
	fastcgi_index index.php;
	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_read_timeout 180;
	include fastcgi_params;
    }

    location ~ \.php/ {
	include fastcgi_params;
	fastcgi_split_path_info ^(.+\.php)(/.*)$;
	fastcgi_param PATH_INFO $fastcgi_path_info;
	fastcgi_param PATH_TRANSLATED $document_root$fastcgi_path_info;
	fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
	fastcgi_pass unix:/var/run/php5-fpm.sock;
   }

    # Zee favicon
    location = /favicon.ico {
	log_not_found off;	
	access_log off;
    }

    # Moar robots pl0x
    location = /robots.txt {
	log_not_found off;
	access_log off;
    }
 

    # redirect server error pages to the static page /50x.html
	error_page 500 502 503 504 /50x.html;
	location = /50x.html {
	root /usr/share/nginx/html;
    }
 
    # deny access to certain none web files
	location ~ /(\.ht|\.user.ini|\.git|\.hg|\.bzr|\.svn) {
	deny all;
    }

    # serve zee static content fast
    location ~ ^/(img|cjs|ccss)/ {
	access_log off;
	expires 7d;
	add_header Cache-Control public;
    }
}
```

## How to use this framework ###
* When testing session close down browser

# Automatic page creation #

1.

* Make these directories writable

* app/models

* app/controllers

* app/views

2. To auto-create new page go to http://yourdomain.com/scaffold/index/example

3. This will automatically build new pages via the scaffold functionality.

* Scaffold pages
* index
* create
* read
* update
* delete
* modify
* update
* login

4. Scaffold library http://sandbox.typekoce.com/scaffold/library/baki
* Orders.php saved to app/lib/baki


## Template page ##
```
	public function sample($data, $models, $options) {

		if (isset($data['directive']) && $data['directive'] === __FUNCTION__) {

			$h1 = __FUNCTION__;
			
			$data = $this -> sheader($data, 'title', "cv");

			$data = $this -> template($data, 'h1', $h1);

			$h2 = 'Enter contact details below';

			$data = $this -> template($data, 'h2', $h2);
			
			//$data = $this -> nav($data, $options);

			//$data = $this -> side($data, $options);

		}

		return $data;

	}
```

## Breadcrumbs ##
```
	$bread = array(
		array('title'=>'add','url'=>array('companies','add'))
	);

	$options = $this->options($options, 'breadcrumbs', $bread);
	
	$data = $this->breadcrumbs($data, $options);
```

## Breadcrumb method ##
```
	public function breadcrumbs($data, $options) {

		$rs = array( 
			array('title' => 'home', 'url'=>array('profile','index')), 
			array('title' => 'companies','url'=>array('companies','index'))
		);
		
		$rs = $this->bakers($rs, $data, $options);

		$rs = $this->bread($rs,$data,$options);

		$data = $this -> spartial($data, __FUNCTION__, $rs);

		return $data;
	}
	
	public function bakers($rs,$data,$options){
		
		$e = 'breadcrumbs';
		
		if(isset($options[$e])){
			
			$rs = array_merge($rs,$options[$e]);
						
		}
		
		
		return $rs;
	}
	
	public function bread($rs,$data,$options){
	
		$counter = 0;

		while($counter<count($rs)){
		
			$m = $rs[$counter];
			
			$m = $this->crumbs($m,$data,$options);
			
			$rs[$counter] = $m;
			
			$counter++;
		
		}

		return $rs;
	}
	
	public function crumbs($m,$data,$options){
	
		$el = __FUNCTION__;

		$url = $this->surl($m['url']);

		$url = $this->html->link($url);
		
		$attr = array(
			'href'=>$url,
		);
		
		$attr = $this->attributes($attr);
		
		$m[$el] = $attr;

		return $m;
	}
	
```

## Add session authentication to page ##
```
	$data = $this->smodey($data, $models, 'documents', 'powerpack',$options);

```

## Add to Controller
```
		$models = array(
			'home' => 'HomeModel',
			'profile'=>'ProfileModel',
			'documents'=>'DocumentsModel'
		);
		
```

## Add navigation and side bar
```
			$data = $this->smodelfn($data, $models, 'profile','nav',$options);
			
			$data = $this->smodelfn($data, $models, 'profile','side',$options);
			
			$data = $this->smodelfn($data, $models, 'profile','admin',$options);

```

## Db total records ##

*Note remember to add relevant models to controller

```
	public function total($data,$options){
		
		$key = __FUNCTION__;

		$value = "";
		
		$conditions = array(
			'table'=>'xmen_users'
		);
		
		$rs = $this->db->total($conditions);

		$data = $this->sdata($data, $key, $value);
		
		return $data;
	}
```

# Db SUM() functionality
```
		$conditions = array(
			'table'=>'horizon_bereavement_expenses',
			'wheres'=>$wheres,
			'sum'=>array(
				'col'=>'amount',
				'name'=>'total'
			)
		);
```

## Load conditions from InitModel ##

*Note remember to add relevant models to controller

```
	$options = $this->options($options, 'collar', 'xmen_portfolio');
	
	$data = $models['scrape']->table($data,$models,$options);
```

## sform ##

```
	public function sform($data, $options) {

		$url = array('signin', 'create');

		//$url = $this -> normal($url, $data, $options);

		$url = $this -> surl($url);

		$options = $this -> options($options, 'protocol', 'HTTPS');

		$url = $this -> html -> link($url, $options);

		$attr = array('method' => 'POST', 'id' => __FUNCTION__, 'name' => __FUNCTION__, 'class' => "form-signin", 'action' => $url);

		$attr = $this -> attributes($attr);

		$data = $this -> template($data, __FUNCTION__, $attr);

		return $data;
	}
```

## File input Attribute method ##

** Use the default attribute to pass value without key pair

```
	public function pict($data,$options){
		
		$attr = array(
			'name'=>__FUNCTION__,
			'type'=>'file',
			'id'=>__FUNCTION__,
			'class'=>__FUNCTION__,
			'data-type'=>__FUNCTION__,
			'default'=>'multiple'
		);
		
		$attr = $this->attributes($attr);
		
		$data = $this->template($data, __FUNCTION__, $attr);
		
		return $data;
	}

```

## sform url ##
```
	$normal = array('kuwahara','resume',$data['id']);
	
	$options = $this->options($options, 'normal',$normal);
	
	public function normal($url, $data, $options){
		
		if(isset($options[__FUNCTION__])){
			
			$url = $options[__FUNCTION__];
			
		}

		return $url;
	}
			
```

## Alias partial element functionality ##
```
	public function alias($m,$data,$options){
				
		foreach ($m as $key => $value) {
			
			if(isset($m['alias'][$key])){
				
				$m[$key] = $m['alias'][$key];
				
			}
						
		}
		
		return $m;
	}
```

## Url attributes ##
```
$url = array(
  'profile',
  'contacts'
);

$url = $this->surl($url);

$url = $this->html->link($url,$options);

$attr = array(
  'href'=>$url
);

$attr = $this->attributes($attr);
```

## Flash with url ##

```
	public function portfolio_flash_share($data,$options){
		
		$id = $this->session->read(array('portfolio_resolution'));
		
		if(isset($id) && !empty($id)){
			
			$url = array(
				'profile',
				'portfolio',
				'guest',
				$id
			);
			
			$url = $this->surl($url);
			
			$url = $this->html->link($url);
			
			$attr = array(
				'href'=>$url,
				'target'=>'_blank'
			);
			
			$attr = $this->attributes($attr);
			
			$options = $this->options($options, 'attr', $attr);
			
			$txt = 'Share your portfolio with this link';
			
			$msg = $this->html->hyper($txt,$options);
			
			$flash = 'info';
							
			$data = $this->flash($data,$msg,$flash);
			
		}
		
		
		return $data;
	}

```


## CSS Page Style ##
```
	$data = $this->smodey($data, $models, 'talktoshirley', 'pagestyle',$options);

```

# Load CSS
```
HTTP://sandbox.typekoce.com/resources/cssstyle/font-awesome-4.7.0/css/font-awesome.css
```


## Facebook response ##
```
https://typekoce.com/signin/update?code=AQAXrA9-grGtEoTtRIaVMHDGdlokmYRdBiUM67CT0aSL-iH8Jvj0oxyXHnvsufKibrLwRKEz9LdKpqIPjYb7BwIbuk3gGDM13td5-Owb_t_9NNOHcnrF0XNGcszJsV07nBcAcG70m4fVh1KP-X565q39UcJOrQhy3kBcmxoONRKAyGySlMg9fkNp-C1pPCyip9NaqgjKYY2dEaJH_ZIvBJU1MA6Bg69plnp55rMTzCSkJc-poW57QHI2XO6WchP47ZSSQe2PG7tML_JEs-Ans1t6Sz-l-GBIieNQmr9qDMF6ikjNNXE-CNEMRHNPfd33ByQ&state=d9ddc1f23a0bc0c252d38023998aca51#_=_

https://www.facebook.com/v2.10/dialog/oauth?client_id=1000275760363846&state=618629aeef8c3f06b0fd5e3393db910f&response_type=code&sdk=php-sdk-5.7.0&redirect_uri=HTTPS%3A%2F%2Ftypekoce.com%2Fsignin%2Fupdate&scope=email

* Cosmina is great!

redirect_uri=HTTPS://typekoce.com/signin/update&scope=email

* McNair

https://dev.typekoce.com/signin/update&scope=email

```

## set flash ##
```
$data = $this->flash($data,'Login error','danger');
```

## Setting options ##

*** Note overwriting the same key may have unpredictable results***

```
$options = $this->options($options,'foo','bar');

```


## Creating a ssl form ##

** Note no remote links on ssl page ie: http:getbootstrap.com/

```
$options = $this->options($options,'protocol','HTTPS');

$url = $this->html->link('model/action/'$options);

```

## Adding attributes
* Remember to pass the
```
$options['form'] = TRUE;

$attr = $this->attributes($attr,$options);

```

## Adding custom route ##
* Note to by pass app routing call controller home and passthrough method

* Add to app/core/Routes.php
```
$this->domain('sandbox.typekoce.com', 'signin', 'read', 'read');
```


## Filtering partials ##

** Pass option
```
$options = $this->options($options, 'impression', 'businessplangoals');
```

```
	public function impression($data,$options){
	
		$key = $options[__FUNCTION__];
		
		$rs = $this->gpartial($data,$key);
		
		$counter = 0;
		
		while($counter<count($rs)){
		
			$m = $rs[$counter];

			new dBug($m);

			$counter++;
		
		}
		
		$data = $this->repartial($data,$key,$rs);
		
		return $data;
	}
```
* Atttributes for partial
```
	public function businessattr($m,$data,$options){
		
		$key = __FUNCTION__;
		
		$attr = array(
			'data-type'=>$key
		);
		
		$attr = $this->attributes($attr);
		
		$m[$key] = $attr;
		
		return $m;
	}
```

## Loading images as a resource ##

* Place image in theme folder then use the url starting with resources/image

* This will request the image via the resources/image controllers

* Functionality can be created to track images in emails, pdf, etc.

```
$logo = $this->html->link('resources/image/bar480/images/hex.png');
```

## Controller ##
```
public function index($id = ''){

  $controller = new Controller();

  $data = array(
    'id'=>$id,
    'directive'=>__FUNCTION__
  );

  $data = $controller->slug($data);

  $model = array(
    __CLASS__=>__CLASS__ . 'Model',
    'home'=>'HomeModel'
  );

  $models = $controller->model($model);

  $data = $models[__CLASS__]->init($data,$models);

  $controller->view(__CLASS__ . '/' . __FUNCTION__,$data);

}
```

## Model ##
```
public function index($data,$options){

  if(isset($data['directive']) && $data['directive'] === __FUNCTION__){

  }

  return $data;

}
```

## Bind all data to $data array ##
```
$data = $this->sdata($data,'w',40);
```

## loading libraries ##
```
$file = 'phpseclib/Net/SSH2.php';

$this->lib($file);

$ssh = new Net_SSH2($data['id']);
```

## controller set-up for loading multiple models ##
```
$model = array(
  __CLASS__=>__CLASS__ . 'Model',
  'home'=>'HomeModel',
  'profile'=>'ProfileModel'
);
```

## loading model functions ##
```
$data = $this->smodelfn($data,$models,'profile','gateway');
```

## loading model data from another model ##
```
public function gdatas($data,$models,$options){

  $datas = array(
    'id'=>1,
    'directive'=>'cv',
    __FUNCTION__=>$data
  );

  $datas = $models['home']->init($datas,$models,$options);

  $data = $this->sdata($data,__FUNCTION__,$datas);

  return $data;
}
```

## Creating partial ##
```
	public function plans($data, $options) {

		$rs = array( 
			array('title' => 'Update', 'icon' => 'file'), 
			array('title' => 'Products', 'icon' => 'shopping-cart')
		);

		$data = $this -> spartial($data, __FUNCTION__, $rs);

		return $data;
	}

```

## Processing partial ##
```
	public function plans($data, $options) {

		$rs = array( 
			array(
				'title' => 'Professional Subscriptions', 
				'modify' => 'modify',
				'status'=>'active',
				'link'=>''
			), 
		);

		$rs = $this->button($rs,$data,$options);

		$data = $this -> spartial($data, __FUNCTION__, $rs);

		return $data;
	}

	public function but($m,$data,$options){
	
		$el = __FUNCTION__;

		new dBug($m);

		return $m;
	}
	
	public function button($rs,$data,$options){
	
		$counter = 0;

		while($counter<count($rs)){
		
			$m = $rs[$counter];
			
			$m = $this->but($m,$data,$options);
			
			$rs[$counter] = $m;
			
			$counter++;
		
		}

		return $rs;
	}
	
```

## Save Many ##

* Save many function

```
	$conditions = array();

	$date = $this->timewarp->now();

	$fields = array(
		'Chorlton Cobblers'=>'Tea &amp; Sticky shoes',
		'Chorlton Ye Olde Cake Shoppe'=>'Tea &amp; Sticky Cakes',
		'Chorlton Ye Olde Computer Repair'=>'New fanglled technology'
	);

	foreach ($fields as $title => $description) {

		$save = array(
			'title' => $title,
			'description' => $description,
			'date'=>$date,
			'status'=>1
		);

		$conditions[] = array(
			'table' => $data['tbl_businesses'],
			'save' => $save,
		);

	}

	new dBug($rs = $this->db->savem($conditions));

$this->db->savem($conditions);

```


## Update data ##
```
$update =array(
	'status'=>1
);

$conditions = array(
  'table'=>'xmen_users',
  'update'=>$update,
  'where'=>array(
    'id'=>$this->session->read(array('profile','id'))
  )
);

new dBug($this->db->update($conditions));
```

### Multiple Update ###
```
			
			$conditions = array();
						
			$counter = 0;
			
			while($counter<count($rs)){
				
				$status = 0;
				
				if($rs[$counter]['id'] === $data['id']){
					
					$status = 1;
					
				}
								
				$condition = array(
					'table'=>$data['tbl'],
					'update'=>array(
						'status'=>$status
					),
					'where'=>array(
						'id'=>$rs[$counter]['id']
					)
				);
				
				$conditions[] = $condition;
				
				$counter++;
				
			}
			
			new dBug($res = $this->db->mupdate($conditions));
```

## Find like ##
```
	$conditions = array(
		'table'=>$data['tbl'],
		'like'=>array(
			'col'=>'title',
			'val'=>$_POST['search']
		)
	);
	
	$rs = $this->db->find($conditions);
```

## Find data ##
```
$conditions = array(
	'table' => 'xmen_cv', 
	'where' => array(
		'col' => 'content', 
		'val' => $this -> db -> string($uid)
	)
);

$rs = $this -> db -> find($conditions);
```

## Find data with specific field names ##

```
		$wheres = array(
			'id'=>$data['id']
		);
        
        $conditions = array(
            'table'=>'migrate_lesson_plans',
            'wheres'=>$wheres,
            'field_names'=>array(
				'aims',
				'aims_1'
			)
        );
```

## Find data with where and ##
```
		$wheres = array(
			'date'=>date("Y-m-d h:i:s", $d),
			'sender'=>$m['sender']
		);
		
		$conditions = array(
			'table'=>'xmen_email_responder',
			'wheres'=>$wheres
		);
		
		$rs = $this->db->find($conditions);
```

## Find data with and or ##

```
		$wheres = array(
			'or'=>array(
				'status'=>array(2),
			),
			'status'=>1
		);
		
		$conditions = array(
			'table'=>'migrate_lesson_todo',
			'order'=>array('id'=>'desc'),
			'wheres'=>$wheres
		);
```

## Find data with json decode ##
```
		$wheres = array(
			'date'=>date("Y-m-d h:i:s", $d),
			'sender'=>$m['sender']
		);
		
		$conditions = array(
			'table'=>'xmen_email_responder',
			'wheres'=>$wheres,,
			'json'=>array(
				'pref'=>'log'
			)
		);
		
		$rs = $this->db->find($conditions);
```

## Find data with field alias ##
```
		$conditions = array(
			'table' => 'xmen_upload', 
			'where' => array(
				'col' => 'folder', 
				'val' => $this -> db -> string($uid)
			),
			'as'=>array(
				'course'=>'title',
				'accreditation'=>'level'
			)
		);
		
		$rs = $this -> db -> find($conditions);
```

## Find data INNER JOIN example

* Add line of code after consitions
```
$conditions = $this->recordjoins($data, $conditions, $options);
```

```
	public function recordjoins($data,$conditions,$options){
		//@TODO: Remove condition if not needed!
		if(!empty($data['bizid']) && !empty($data['page'])){
			
			$conditions['joins']=array(						
				array(
					'type'=>'INNER',
					'on'=>array(
						'tbl1'=>'content',
						'tbl2'=>'id'
					),
					'table'=>'biz_registration',
					'fields'=>array(
						'id'=>'biz_id',
						'userid'=>'biz_userid',
						'title'=>'biz_title',
						'description'=>'biz_description',
						'status'=>'biz_status'
					)
				),							
			);			
		}
		
		return $conditions;
		
	}
```

# Joins Many

```
<?php

// Assuming DB_HOST, DB_USER, DB_PASSWORD, DB_NAME are defined globally
// in your application's configuration (e.g., config.php)

require_once 'App/Core/Db.php'; // Adjust path as per your project structure

use App\Core\Db;

$db = new Db();

$conditions = [
    'table' => 'users', // Main table
    'joins_many' => [
        [
            'type' => 'LEFT',
            'table' => 'products',
            'on' => [
                'id' => 'user_id' // users.id = products.user_id
            ]
        ],
        [
            'type' => 'INNER',
            'table' => 'carts',
            'on' => [
                'id' => 'user_id' // users.id = carts.user_id
            ]
        ],
        [
            'type' => 'LEFT',
            'table' => 'orders',
            'on' => [
                'id' => 'cart_id', // carts.id = orders.cart_id (This assumes cart_id in orders refers to carts.id)
            ]
        ]
    ],
    'fields' => [
        'users.name' => 'user_name',
        'products.name' => 'product_name',
        'products.price' => 'product_price',
        'orders.quantity' => 'order_quantity'
    ],
    'limit' => 10
];

$result = $db->find($conditions);

if ($result['success']) {
    echo "Query executed successfully.\n";
    echo "Generated SQL:\n" . $result['sql'] . "\n\n";
    echo "Results:\n";
    print_r($result['result']);
} else {
    echo "Query failed: " . $result['error'] . "\n";
}

?>
Generated SQL (simplified for clarity):


SELECT
    users.`id`,
    users.`name` AS user_name,
    products.`name` AS product_name,
    products.`price` AS product_price,
    orders.`quantity` AS order_quantity
FROM `users`
LEFT JOIN `products` ON users.`id` = `products`.`user_id`
INNER JOIN `carts` ON users.`id` = `carts`.`user_id`
LEFT JOIN `orders` ON carts.`id` = `orders`.`cart_id`
LIMIT 10;
```

## Find data with count
```
		$conditions = array(
			'table'=>'horizon_bereavement_expenses',
			'subquery_count'=>array(
				'col'=>'id',
				'name'=>'total_rows'
			)
		);

```

## Email template ##
```
http://sandbox.typekoce.com/email/index
```

## Post data purified ##
```
$this->request->purity();

$this->request->data['post'];

```

## Post Advance datas
```
$this->request->post();

new dBug($this->request->data);
```

## Phpseclib
1.0

Download from http://phpseclib.sourceforge.net/


1.1
Copy files to /app/lib/

1.2

1.3 Install PEAR
wget http://pear.php.net/go-pear.phar
$ php go-pear.phar

apt-get install php5-pgsql php-pear
pear channel-discover phpseclib.sourceforge.net
sudo pear remote-list -c phpseclib
pear install phpseclib/Net_SSH2
service nginx restart

##TODO

* SSL

* attributes

* Scaffold

## Test domain (local machine)
* sandbox.typekoce.com

* http://sandbox.typekoce.com/home/cv

* http://sandbox.typekoce.com/cv/read/1
