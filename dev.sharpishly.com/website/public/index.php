<?php

// $dir = dirname(dirname(__FILE__));

// require_once $dir . '/env.php';

// $vendor = $dir . "/vendor/autoload.php";

// require $vendor;

// use App\Core\App;

// require_once $dir . '/app/core/App.php';
// // IMPORTANT: Call session_start() ONLY ONCE and EARLY
// if (session_status() == PHP_SESSION_NONE) { // Prevent multiple calls
//     session_start();
// }

// // Add these debugging logs to your index.php
// error_log("INDEX.PHP: Script start.");
// error_log("INDEX.PHP: Session ID: " . session_id());
// error_log("INDEX.PHP: Current \$_SESSION content (before App init): " . print_r($_SESSION, true));

// $conf['dir'] = $dir;

// $app = new App($conf);

// debug_db.php
// Prints database credentials to diagnose connection issues
header('Content-Type: text/plain');

// Load env.php to ensure credentials are defined
// require_once __DIR__ . '/env.php';

echo $dir = dirname(dirname(__FILE__));

require_once $dir . '/env.php';

// Output the database credentials
echo "Database Credentials:\n";
echo "DB_HOST: " . DB_HOST . "\n";
echo "DB_USER: " . DB_USER . "\n";
echo "DB_PASSWORD: " . DB_PASSWORD . "\n";
echo "DB_NAME: " . DB_NAME . "\n";

// Optional: Output $_SERVER['HTTP_HOST'] to verify domain
echo "\nServer Host: " . $_SERVER['HTTP_HOST'] . "\n";

die();
?>

