<?php

    // root directory
    $root = dirname(dirname(dirname(__FILE__)));

    require_once $root . '/vendor/Nats/Nats.php';

    use Nats\Nats;

    $nats = new Nats('192.168.0.22', 4222); // Replace with your NATS server address

    echo "Subscription script";

    print_r(array('nats'=>$nats));

    try {

        $subject = 'XMen';

        $nats->connect();

        echo "Connected to Nats server\n";

        $nats->subscribe($subject, function ($subject, $data, $replyTo) {

            echo "Callback\n";

        });

        echo "Start 60 seconds wait\n";

        $nats->wait(60);

        $nats->close();


    } catch(\Exception $e){

        echo 'Error:' . $e->getMessage();

    }

?>