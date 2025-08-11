<?php

$dir = dirname(dirname(__FILE__));

require_once $dir . '/env.php';

$vendor = $dir . "/vendor/autoload.php";

require $vendor;

use App\Core\App;

require_once $dir . '/app/core/App.php';
// IMPORTANT: Call session_start() ONLY ONCE and EARLY
if (session_status() == PHP_SESSION_NONE) { // Prevent multiple calls
    session_start();
}

// Add these debugging logs to your index.php
error_log("INDEX.PHP: Script start.");
error_log("INDEX.PHP: Session ID: " . session_id());
error_log("INDEX.PHP: Current \$_SESSION content (before App init): " . print_r($_SESSION, true));

$conf['dir'] = $dir;

$app = new App($conf);

