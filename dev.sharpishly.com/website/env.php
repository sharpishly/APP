<?php

// Facebook Developer Password
// X%ayu#F=4E&Hrp%

//@TODO: Remember to update 
//@TODO: /etc/hosts
//@TODO: /core/App.php

//https://dash.infinityfree.com/register
//@TODO: user:paul@sharpishly.com
//@TODO: pass:.3,AX+KZQtmNc6K

// Grok password: GR2"U_96W_mj!nF
// grok user: paul@sharpishly.com

// Zoho API 
// password: duxbob-qykba9-fyVwob
// User: paultypekoce@gmail.com 
// https://sharpishly.com/zoho/callback
// Client ID: 1000.RYS1Z1VZ4UYZTQ2DO6F4NDCHFVSKIT
// Client Secret: df561cda0becce6a868ab857da4ea3e8972a76e81b
// URL TO SET CRM: https://chatgpt.com/c/687915a1-50e4-800a-b406-a741936a2a5e

$docker = array(
    'admin.sharpishly.com',
    'test.sharpishly.com'
);

$digitalocean = array(
    'sharpishly.com',
    'dev.sharpishly.com',
    'admin.sharpishly.com'
);

if(in_array($_SERVER['HTTP_HOST'],$digitalocean)){


    /** Database name */
    define( 'DB_NAME', 'sharpishly' );

    /** Database username */
    define( 'DB_USER', 'sharpuser' );

    /** Database password */
    define( 'DB_PASSWORD', 'Addy@789xx$' );

    /** Database hostname */
    define( 'DB_HOST', '127.0.0.1' );

}else if(in_array($_SERVER['HTTP_HOST'],$docker)){

    //@TODO: Docker settings

    /** Database name */
    define( 'DB_NAME', getenv('DB_NAME') );

    /** Database username */
    define( 'DB_USER', getenv('DB_USER') );

    /** Database password */
    define( 'DB_PASSWORD', getenv('DB_PASSWORD'));

    /** Database hostname */
    define( 'DB_HOST', getenv('DB_HOST') );

} else {

    //@TODO: Virtual Box settings

    /** Database name */
    define( 'DB_NAME', 'sharpishly' );

    /** Database username */
    define( 'DB_USER', 'root' );

    /** Database password */
    define( 'DB_PASSWORD', 'Addy@789xx$' );

    /** Database hostname */
    define( 'DB_HOST', '127.0.0.1' );

}

