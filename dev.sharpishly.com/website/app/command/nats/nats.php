<?php

    // root directory
    $root = dirname(dirname(dirname(__FILE__)));

    require_once $root . '/vendor/Nats/Nats.php';

    use Nats\Nats;

    $nats = new Nats('192.168.0.22', 4222); // Replace with your NATS server address

    print_r(array('nats'=>$nats));

    try {

        $subject = 'XMen';

        $nats->connect();

        $nats->publish($subject,'bar');

        sleep(180);

        $nats->close();


    } catch(\Exception $e){

        echo 'Error:' . $e->getMessage();

    }

?>