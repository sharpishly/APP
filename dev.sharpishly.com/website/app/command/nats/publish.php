<?php

	include_once 'config.php';

	// --- Publisher (Order Submission) ---
	function publishOrders($nats)
	{
        print_r(array('nats'=>$nats));
		try {
			$nats->connect();

			for ($i = 0; $i < 1; $i++) {
				$orderId = uniqid();
				$replyTo = "order.reply." . $orderId;

				$orderData = [
					'order_id' => $orderId,
					'customer_id' => rand(100, 200),
					'items' => ['item1', 'item2', 'item' . rand(3, 5)],
					'amount' => rand(50, 200),
				];

                print_r(array('orderData'=>$orderData));

				$nats->publish('orders.process', json_encode($orderData), $replyTo);

				$nats->subscribe($replyTo, function ($subject, $data, $replyTo) use ($orderId) {

					try {
						$response = json_decode($data, true);
						print_r(array('response'=>$response));
						if ($response['status'] === 'success') {
							echo "Order $orderId processed successfully. Transaction ID: " . 
							$response['transaction_id'] . 
							"\n";
						} else {
							echo "Order $orderId failed: " . 
							$response['error'] . 
							"\n";
						}
					} catch(Exception $e){
						echo 'Error: ' . $e->getMessage() . "\n";
					}
				});
			}

			$startTime = microtime(true);
			$nats->wait(60); //wait for 60 seconds.
			$endTime = microtime(true);
			echo "Wait time: " . ($endTime - $startTime) . " in seconds\n";
			$nats->close();

		} catch (\Exception $e) {
			echo "Publisher Error: " . $e->getMessage() . "\n";
		}
	}

    /**
     * Publish Orders
     */
    publishOrders($nats);
?>