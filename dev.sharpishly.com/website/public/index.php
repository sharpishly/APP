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

$conf['dir'] = $dir;

$app = new App($conf);

