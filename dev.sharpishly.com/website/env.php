<?php

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

# Move ssh keys to .env file

