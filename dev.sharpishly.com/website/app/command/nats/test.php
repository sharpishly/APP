<?php

require_once '../config.php';

$nats->connect();
//$rand = string Rand(0,10);
$nats->publish('test.subject', 'Hello from LEMP');
echo "Message Published\n";
$nats->close();
?>
