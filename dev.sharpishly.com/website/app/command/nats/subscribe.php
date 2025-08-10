<?php

// root directory
$root = dirname(dirname(dirname(__FILE__)));

// Not using composer
require_once $root . '/vendor/Nats/Nats.php';

use Nats\Nats;

$nats = new Nats('192.168.0.22', 4222);
$nats->connect();

$nats->subscribe('test.subject', function ($message) {
    if (is_string($message)) { // Check if $message is a string
        echo "Received message: " . $message . "\n"; // Directly use the string
    } else {
        echo "Received message: " . $message->getBody() . "\n"; // Use getBody() if it's an object
    }
});

echo "Subscribed to test.subject. Waiting for messages...\n";

// Keep the script running to receive messages.
while (true) {
    $nats->wait(1); // Check for messages every 1 second
}

$nats->close(); // This will likely not be reached in this simple example.
?>
