<?php
# env.php

$docker = array(
    'admin.sharpishly.com',
    'test.sharpishly.com',
    'sharpishly.com', // Added to include production domain in Docker environment
    'dev.sharpishly.com' // Added to include development domain in Docker environment
);
$digitalocean = array(
    // Removed 'sharpishly.com' and 'dev.sharpishly.com' to avoid using localhost in Docker
    'admin.sharpishly.com'
);

if (in_array($_SERVER['HTTP_HOST'], $digitalocean)) {
    /** Database name for DigitalOcean non-Docker environment */
    define('DB_NAME', 'sharpishly');
    /** Database username */
    define('DB_USER', 'sharpuser');
    /** Database password */
    define('DB_PASSWORD', 'Addy@789xx$');
    /** Database hostname */
    define('DB_HOST', '127.0.0.1');
} elseif (in_array($_SERVER['HTTP_HOST'], $docker)) {
    /** Database name for Docker environment, sourced from environment variable */
    define('DB_NAME', getenv('DB_NAME')); // Uses sharpishly_db from docker-compose.yml
    /** Database username for Docker environment */
    define('DB_USER', getenv('DB_USER')); // Uses sharpishly_user from docker-compose.yml
    /** Database password for Docker environment */
    define('DB_PASSWORD', getenv('DB_PASSWORD')); // Uses Addy@789xx$ from docker-compose.yml
    /** Database hostname for Docker environment */
    define('DB_HOST', getenv('DB_HOST')); // Uses 'db' to resolve to app-db-1 container
} else {
    // VirtualBox or local development settings
    /** Database name */
    define('DB_NAME', 'sharpishly');
    /** Database username */
    define('DB_USER', 'root');
    /** Database password */
    define('DB_PASSWORD', 'Addy@789xx$');
    /** Database hostname */
    define('DB_HOST', '127.0.0.1');
}

// Note: SSH keys should be moved to .env file for security, as they are not used here