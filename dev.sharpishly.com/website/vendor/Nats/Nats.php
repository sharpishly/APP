<?php

namespace Nats;

class Nats
{
    private $socket;
    private $host;
    private $port;
    private $isConnected = false;
    private $pendingSubscriptions = []; // Track pending subscriptions

    public function __construct(string $host = '127.0.0.1', int $port = 4222)
    {
        $this->host = $host;
        $this->port = $port;
    }

    public function connect()
    {
        $this->socket = fsockopen($this->host, $this->port, $errno, $errstr, 5); // Timeout of 5 seconds

        if (!$this->socket) {
            throw new \Exception("Could not connect to NATS: $errstr ($errno)");
        }

        $this->isConnected = true;

        //Handle the +OK and INFO messages.
        $info = $this->read();
        if(strpos($info, 'INFO') !== 0){
          throw new \Exception("NATS server did not return INFO message");
        }
    }

    public function publish(string $subject, string $data, string $replyTo = null)
    {
        if (!$this->isConnected) {
            throw new \Exception("NATS connection is not established.");
        }

        $payload = "PUB $subject " . ($replyTo ? "$replyTo " : "") . strlen($data) . "\r\n$data\r\n";
        $this->write($payload);
    }

    public function subscribe(string $subject, callable $callback, string $queueGroup = null, string $subId = null)
    {
        if (!$this->isConnected) {
            throw new \Exception("NATS connection is not established.");
        }

        if ($subId === null) {
            $subId = uniqid(); // Generate a unique subscription ID
        }

        $this->pendingSubscriptions[$subId] = [
            'subject' => $subject,
            'callback' => $callback,
            'queueGroup' => $queueGroup,
            'received' => false,
        ];

        $payload = "SUB $subject " . ($queueGroup ? "$queueGroup " : "") . "$subId\r\n";
        $this->write($payload);

        return $subId; // Return the subscription ID
    }

    public function unsubscribe(string $subId) {
        if (!$this->isConnected) {
            throw new \Exception("NATS connection is not established.");
        }
        $payload = "UNSUB $subId\r\n";
        $this->write($payload);
        unset($this->pendingSubscriptions[$subId]); // Remove from pending subscriptions
    }

    public function close()
    {
        if ($this->isConnected) {
            fclose($this->socket);
            $this->isConnected = false;
        }
    }

    private function write(string $data)
    {
        fwrite($this->socket, $data);
    }

    private function read()
    {
        $buffer = "";
        while (!feof($this->socket)) {
            $buffer .= fgets($this->socket);
            if(substr($buffer, -2) == "\r\n"){
              break;
            }
        }
        return $buffer;
    }

    public function ping() {
      $this->write("PING\r\n");
      return $this->read();
    }

    public function wait(int $timeout = 30)
    {
        $startTime = time();

        while (!empty($this->pendingSubscriptions) && (time() - $startTime) < $timeout) {
            $message = $this->read();

            if (strpos($message, 'MSG') === 0) {
                $parts = explode(" ", $message, 5);
                if (count($parts) >= 4) {
                    $subject = $parts[1];
                    $subId = $parts[2];
                    $replyTo = isset($parts[3]) && is_numeric($parts[3]) ? null : $parts[3];
                    $dataLength = intval(trim($parts[count($parts)-2]));
                    $data = substr($parts[count($parts) -1], 0, $dataLength);

                    if (isset($this->pendingSubscriptions[$subId])) {
                        $this->pendingSubscriptions[$subId]['callback']($subject, $data, $replyTo);
                        $this->pendingSubscriptions[$subId]['received'] = true;
                        unset($this->pendingSubscriptions[$subId]); // Remove subscription after message received.
                    }
                }
            } else if (strpos($message, 'PING') === 0){
              $this->write("PONG\r\n");
            } else if (strpos($message, '+OK') === 0){
              //Do nothing, just acknowledge.
            }
            usleep(10000); // Sleep for 10ms to avoid busy-waiting
        }

        if (!empty($this->pendingSubscriptions)) {
            print_r(array('pendingSubscriptions'=>$this->pendingSubscriptions));
            echo "Timeout: Not all responses received.\n";
        }
    }
}
?>