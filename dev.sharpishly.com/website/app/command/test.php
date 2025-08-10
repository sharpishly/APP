<?php

// root directory
$root = dirname(dirname(dirname(__FILE__)));

// Not using composer
require_once $root . '/vendor/Nats/Nats.php';

use Nats\Nats;

$nats = new Nats('192.168.0.22', 4222);
$nats->connect();
//$rand = string Rand(0,10);
$nats->publish('test.subject', 'Hello from LEMP');
echo "Message Published\n";
$nats->close();
?>
