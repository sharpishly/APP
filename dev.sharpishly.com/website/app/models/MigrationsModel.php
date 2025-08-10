<?php

namespace App\Models;

use dBug\dBug;

class MigrationsModel extends Model{


    public $migrate_registration_details = array(
        'table' => 'registration_details',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'email' => 'VARCHAR(255)',
            'password' => 'VARCHAR(255)',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_personal_details = array(
        'table' => 'personal_details',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'firstname' => 'VARCHAR(255)',
            'lastname' => 'VARCHAR(255)',
            'registration_number' => 'VARCHAR(255)',
            'industry_sector' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_customerlover_details = array(
        'table' => 'customerlover_details',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'business_name' => 'VARCHAR(255)',
            'owner_name' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'phone' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

	public $migrate_company_details = array(
        'table' => 'company_details',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'name' => 'VARCHAR(255)',
            'registration' => 'VARCHAR(255)',
            'sector' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

	public $migrate_project_details = array(
        'table' => 'project_details',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'name' => 'VARCHAR(255)',
            'description' => 'VARCHAR(255)',
            'sector' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_business_plan_tasks = array(
        'table' => 'business_plan_tasks',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'month' => 'VARCHAR(255)',
            'TaskDescription' => 'TEXT',
            'Priority' => 'TINYINT CHECK(Priority BETWEEN 1 AND 5)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_funding = array(
        'table' => 'funding',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'Description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_phd = array(
        'table' => 'phd',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_todo = array(
        'table' => 'todo',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_todo_notes = array(
        'table' => 'todo_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_interviews = array(
        'table' => 'interviews',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_interviews_notes = array(
        'table' => 'interviews_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );


    public $migrate_projects = array(
        'table' => 'projects',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_projects_notes = array(
        'table' => 'projects_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );


    public $migrate_api = array(
        'table' => 'api',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_api_notes = array(
        'table' => 'api_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_google = array(
        'table' => 'google',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_google_notes = array(
        'table' => 'google_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_blog = array(
        'table' => 'blog',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_blog_notes = array(
        'table' => 'blog_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_github = array(
        'table' => 'github',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_github_notes = array(
        'table' => 'github_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );


    public $migrate_securityarchitect = array(
        'table' => 'securityarchitect',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_securityarchitect_notes = array(
        'table' => 'securityarchitect_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );


    public $migrate_businesses = array(
        'table' => 'businesses',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_businesses_notes = array(
        'table' => 'businesses_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );



    public $migrate_kali = array(
        'table' => 'kali',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_kali_notes = array(
        'table' => 'kali_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    // Users
    public $migrate_users = array(
        'table' => 'users',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'first_name' => 'VARCHAR(255)',
            'last_name' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255) UNIQUE',
            'password' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    // Google user tokens
    public $migrate_user_tokens = array(
        'table' => 'user_tokens',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'user_id' => 'INT', //-- Link to your application's users table
            'access_token' => 'TEXT',
            'refresh_token' => 'TEXT',
            'expiry_time' => 'TIMESTAMP NULL', //-- Optional: Store token expiry if provided by Google
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    // products
    public $migrate_products = array(
        'table' => 'products',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(255)',        // Changed from 'title' to 'name'
            'description' => 'TEXT',      // Changed from VARCHAR(255) to TEXT
            'price' => 'DECIMAL(10, 2)', // Changed from VARCHAR(255) to DECIMAL
            'stock' => 'INT',             // Added 'stock' field
            'image' => 'VARCHAR(255)',       // Added 'image' field
            // 'quantity' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status' => 'INT(255)'
        ),
        'prefix' => 'migrate_'
    );
    

    public $migrate_products_notes = array(
        'table' => 'products_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    // products
    public $migrate_products_cart = array(
        'table' => 'products_cart',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'user_id' => 'INT(100)',  // Foreign key to users table
            'product_id' => 'INT(100)', // Foreign key to products table
            // 'title' => 'VARCHAR(255)',
            // 'description' => 'TEXT',
            'quantity' => 'INT',
            'price' => 'DECIMAL(10,2)', // Added price,  redundant?
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP', // Renamed 'date' to 'created_at' and set default
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_products_orders = array(
        'table' => 'products_orders',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'user_id' => 'INT(100)', // Foreign key to users table
            'order_number' => 'VARCHAR(20) UNIQUE', // Unique order identifier
            'total_amount' => 'DECIMAL(10, 2)',
            'status'=>'INT(255)',
            'shipping_address_id' => 'INT(100)', // Foreign key to a shipping_addresses table
            'billing_address_id' => 'INT(100)',  // Foreign key to a billing_addresses table
            'payment_method' => 'VARCHAR(50)', // e.g., "credit_card", "paypal"
            'transaction_id' => 'VARCHAR(255)', // Store transaction ID from payment gateway
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_product_order_item = array(
        'table' => 'product_order_item',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'order_id' => 'INT(100)', // Foreign key to orders table
            'product_id' => 'INT(100)', // Foreign key to products table
            'quantity' => 'INT',
            'price' => 'DECIMAL(10, 2)', // Price of the item at the time of order
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ),
        'prefix' => 'migrate_'
    );


    // New shipping addresses table
    public $migrate_shipping_addresses = array(
        'table' => 'shipping_addresses',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'user_id' => 'INT(100)', // Link to the users table
            'full_name' => 'VARCHAR(255)',
            'address_line1' => 'VARCHAR(255)',
            'address_line2' => 'VARCHAR(255) NULL',
            'city' => 'VARCHAR(100)',
            'state_province' => 'VARCHAR(100) NULL',
            'postal_code' => 'VARCHAR(20)',
            'country' => 'VARCHAR(100)',
            'phone_number' => 'VARCHAR(50) NULL', // Optional phone number
            'is_default' => 'TINYINT(1) DEFAULT 0', // To mark a default address for a user
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP',
            'status' => 'INT(255)' // Assuming a status column for all tables
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_seeds = array(
        'table' => 'seeds',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_admin = array(
        'table' => 'admin',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_admin_notes = array(
        'table' => 'admin_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_vacancies = array(
        'table' => 'vacancies',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'userid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'email' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

    public $migrate_vacancies_notes = array(
        'table' => 'vacancies_notes',
        'create' => array(
            'id' => 'INT(100) PRIMARY KEY AUTO_INCREMENT',
            'noteid' => 'VARCHAR(255)',
            'title' => 'VARCHAR(255)',
            'description' => 'TEXT',
            'url' => 'VARCHAR(255)',
            'pref' => 'TEXT',
            'content' => 'LONGTEXT',
            'date' => 'DATETIME',
            'status'=>'INT(255)'
        ),
        'prefix' => 'migrate_'
    );

	public function main($data,$models,$options=false){

		//TODO: Add explict url method
		$options['explict'] = TRUE;
			
		$list = array(
			'home'=>'/',
			'Migrate:index'=>'/migrations',
            'Migrate:read'=>'/migrations/read',
			'Migrate:save'=>'/migrations/save',

		);
		
		$data = $this->set($data, 'list', $list);		

		$data = $this->index($data, $models, $options);

        $data = $this->read($data, $models, $options);

		// $data = $this->save($data, $models, $options);

        new dBug($data);
		
		return $data;
	}
	
	public function index($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'index'){

			$data = $this->migrate_registration_details($data,$options);

            $data = $this->migrate_personal_details($data,$options);

			$data = $this->migrate_company_details($data,$options);

			$data = $this->migrate_project_details($data,$options);

            $data = $this->migrate_business_plan_tasks($data,$options);

            $data = $this->migrate_funding($data,$options);

            $data = $this->migrate_phd($data,$options);

            $data = $this->migrate_todo($data,$options);

            $data = $this->migrate_todo_notes($data,$options);

            $data = $this->migrate_customerlover_details($data,$options);

            $data = $this->migrate_interviews($data,$options);

            $data = $this->migrate_interviews_notes($data,$options);

            $data = $this->migrate_projects($data,$options);

            $data = $this->migrate_projects_notes($data,$options);

            $data = $this->migrate_api($data,$options);

            $data = $this->migrate_api_notes($data,$options);

            $data = $this->migrate_google($data,$options);

            $data = $this->migrate_google_notes($data,$options);

            $data = $this->migrate_blog($data,$options);

            $data = $this->migrate_blog_notes($data,$options);

            $data = $this->migrate_github($data,$options);

            $data = $this->migrate_github_notes($data,$options);

            $data = $this->migrate_securityarchitect($data,$options);

            $data = $this->migrate_securityarchitect_notes($data,$options);

            $data = $this->migrate_businesses($data,$options);

            $data = $this->migrate_businesses_notes($data,$options);

            $data = $this->migrate_kali($data,$options);

            $data = $this->migrate_kali_notes($data,$options);

            $data = $this->migrate_users($data,$options);

            $data = $this->migrate_user_tokens($data,$options);

            $data = $this->migrate_products($data,$options);

            $data = $this->migrate_products_notes($data,$options);

            $data = $this->migrate_products_cart($data,$options);

            $data = $this->migrate_products_orders($data,$options);

            //migrate_product_order_item
            $data = $this->migrate_product_order_item($data,$options);

            //migrate_shipping_addresses
            $data = $this->migrate_shipping_addresses($data,$options);
            
            //migrate_seeds
            $data = $this->migrate_seeds($data,$options);

            $data = $this->migrate_admin($data,$options);

            $data = $this->migrate_vacancies($data,$options);

            $data = $this->migrate_vacancies_notes($data,$options);
			
			$data = $this->set($data, 'title', 'Migrations');

			
		}
		
		return $data;
		
	}

	public function read($data,$models,$options){
		
		if(isset($data['directive']) && $data['directive'] === 'read'){
	
			$conditions = array(
				'table'=>'migrate_registration_details',
				'order'=>array('id'=>'DESC'),
				'limit'=>'0,10'
			);
			
			$rs = $this->db->find($conditions);
						
			$data = $this->set($data, 'accounts', $rs);
			
			$data = $this->set($data, 'title', 'Home');

			
		}
		
		return $data;
		
	}


    public function migrate_registration_details($data,$options=false){
        
        $conditions = $this -> migrate_registration_details;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_personal_details($data,$options=false){
        
        $conditions = $this -> migrate_personal_details;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_company_details($data,$options=false){
        
        $conditions = $this -> migrate_company_details;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_project_details($data,$options=false){
        
        $conditions = $this -> migrate_project_details;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }
    
    public function migrate_business_plan_tasks($data,$options=false){
        
        $conditions = $this -> migrate_business_plan_tasks;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }
    
    public function migrate_funding($data,$options=false){
        
        $conditions = $this -> migrate_funding;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_phd($data,$options=false){
        
        $conditions = $this -> migrate_phd;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_todo($data,$options=false){
        
        $conditions = $this -> migrate_todo;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_todo_notes($data,$options=false){
        
        $conditions = $this -> migrate_todo_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_customerlover_details($data,$options=false){
        
        $conditions = $this -> migrate_customerlover_details;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_interviews($data,$options=false){
        
        $conditions = $this -> migrate_interviews;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_interviews_notes($data,$options=false){
        
        $conditions = $this -> migrate_interviews_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_projects($data,$options=false){
        
        $conditions = $this -> migrate_projects;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_projects_notes($data,$options=false){
        
        $conditions = $this -> migrate_projects_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_api($data,$options=false){
        
        $conditions = $this -> migrate_api;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_api_notes($data,$options=false){
        
        $conditions = $this -> migrate_api_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_google($data,$options=false){
        
        $conditions = $this -> migrate_google;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_google_notes($data,$options=false){
        
        $conditions = $this -> migrate_google_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_blog($data,$options=false){
        
        $conditions = $this -> migrate_blog;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_blog_notes($data,$options=false){
        
        $conditions = $this -> migrate_blog_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_github($data,$options=false){
        
        $conditions = $this -> migrate_github;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_github_notes($data,$options=false){
        
        $conditions = $this -> migrate_github_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_securityarchitect($data,$options=false){
        
        $conditions = $this -> migrate_securityarchitect;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_securityarchitect_notes($data,$options=false){
        
        $conditions = $this -> migrate_securityarchitect_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_businesses($data,$options=false){
        
        $conditions = $this -> migrate_businesses;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_businesses_notes($data,$options=false){
        
        $conditions = $this -> migrate_businesses_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_kali($data,$options=false){
        
        $conditions = $this -> migrate_kali;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_kali_notes($data,$options=false){
        
        $conditions = $this -> migrate_kali_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_users($data,$options=false){
        
        $conditions = $this -> migrate_users;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_user_tokens($data,$options=false){
        
        $conditions = $this -> migrate_user_tokens;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    // products
    public function migrate_products($data,$options=false){
        
        $conditions = $this -> migrate_products;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_products_notes($data,$options=false){
        
        $conditions = $this -> migrate_products_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_products_orders($data,$options=false){
        
        $conditions = $this -> migrate_products_orders;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    //
    public function migrate_shipping_addresses($data,$options=false){
        
        $conditions = $this -> migrate_shipping_addresses;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    //
    public function migrate_product_order_item($data,$options=false){
        
        $conditions = $this -> migrate_product_order_item;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_products_cart($data,$options=false){
        
        $conditions = $this -> migrate_products_cart;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }
	
    //migrate_seeds
    public function migrate_seeds($data,$options=false){
        
        $conditions = $this -> migrate_seeds;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_admin($data,$options=false){
        
        $conditions = $this -> migrate_admin;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_admin_notes($data,$options=false){
        
        $conditions = $this -> migrate_admin_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_vacancies($data,$options=false){
        
        $conditions = $this -> migrate_vacancies;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }

    public function migrate_vacancies_notes($data,$options=false){
        
        $conditions = $this -> migrate_vacancies_notes;

        $result = $this -> db -> create($conditions);

		$data = $this->set($data,__FUNCTION__,$result);
        
        return $data;
    }
}

?>