<?php

$server = '192.168.0.22:4222'; // NATS server address and port
$httpPort = 8222; // NATS HTTP monitoring port

function getNatsServerInfo($server) {
    $parts = explode(':', $server);
    $host = $parts[0];
    $port = isset($parts[1]) ? (int)$parts[1] : 4222;

    $socket = fsockopen($host, $port, $errno, $errstr, 2); // 2-second timeout

    if (!$socket) {
        return false; // Connection failed
    }

    $request = "CONNECT {\"verbose\":false,\"pedantic\":false,\"user\":\"\",\"pass\":\"\",\"name\":\"php-client\",\"lang\":\"php\",\"version\":\"1.0.0\"}\r\n";
    fwrite($socket, $request);

    $response = fgets($socket); // Read the +OK or -ERR
    if (strpos($response, '+OK') === false) {
        fclose($socket);
        return false; // Connection or auth error.
    }

    fwrite($socket, "INFO\r\n");
    $infoResponse = "";
    while (!feof($socket)) {
        $infoResponse .= fgets($socket);
        if (strpos($infoResponse, "\r\n{\r\n") !== false) {
            break;
        }
    }

    $infoResponse = trim(substr($infoResponse, strpos($infoResponse, "{\r\n")));

    fclose($socket);

    $info = json_decode($infoResponse, true);
    return $info;
}

function getHttpVarz($httpPort) {
    $socket = fsockopen('localhost', $httpPort, $errno, $errstr, 2);

    if (!$socket) {
        return false;
    }

    $request = "GET /varz HTTP/1.1\r\nHost: localhost\r\nConnection: close\r\n\r\n";
    fwrite($socket, $request);

    $response = "";
    while (!feof($socket)) {
        $response .= fgets($socket);
    }

    fclose($socket);

    // Extract the JSON part from the HTTP response
    $jsonStart = strpos($response, '{');
    if ($jsonStart === false) {
        return false;
    }

    $json = substr($response, $jsonStart);
    $data = json_decode($json, true);

    return $data;
}

$serverInfo = getNatsServerInfo($server);

if ($serverInfo) {
    if (isset($serverInfo['subscriptions']) && is_numeric($serverInfo['subscriptions'])) {
        echo "Total Subscriptions: " . $serverInfo['subscriptions'] . "\n";

        $varzData = getHttpVarz($httpPort);
        if ($varzData && isset($varzData['subscriptions'])) {
            echo "Subscriptions (from /varz): " . $varzData['subscriptions'] . "\n";
        } else {
            echo "Failed to retrieve /varz data.\n";
        }
    } else {
        echo "No subscriptions found.\n";
    }
} else {
    echo "Failed to connect to NATS server.\n";
}
?>