<?php

namespace Hotmail;

class Hotmail {
    private $smtp_server = "smtp.office365.com";
    private $smtp_port = 587;
    private $username;
    private $password;
    private $from_email;
    private $from_name;

    public function __construct($from_email, $password, $from_name = '') {
        $this->username = $from_email;
        $this->password = $password;
        $this->from_email = $from_email;
        $this->from_name = $from_name;
    }

    public function sendEmail($to, $subject, $message) {
        $socket = fsockopen($this->smtp_server, $this->smtp_port, $errno, $errstr, 30);
        if (!$socket) {
            return "Connection failed: $errno - $errstr";
        }

        $this->serverResponse($socket);
        $this->sendCommand($socket, "EHLO " . gethostname());
        $this->sendCommand($socket, "STARTTLS");
        stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT);

        $this->sendCommand($socket, "EHLO " . gethostname());
        $this->sendCommand($socket, "AUTH LOGIN");
        $this->sendCommand($socket, base64_encode($this->username));
        $this->sendCommand($socket, base64_encode($this->password));

        $this->sendCommand($socket, "MAIL FROM:<{$this->from_email}>");
        $this->sendCommand($socket, "RCPT TO:<{$to}>");
        $this->sendCommand($socket, "DATA");

        $headers = "From: " . ($this->from_name ? '"' . $this->from_name . '" ' : '') . "<{$this->from_email}>\r\n";
        $headers .= "Reply-To: {$this->from_email}\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        $email_body = "{$headers}\r\nSubject: {$subject}\r\n\r\n{$message}\r\n.\r\n";
        $this->sendCommand($socket, $email_body);

        $this->sendCommand($socket, "QUIT");
        fclose($socket);

        return "Email sent successfully.";
    }

    private function sendCommand($socket, $command) {
        fputs($socket, $command . "\r\n");
        return $this->serverResponse($socket);
    }

    private function serverResponse($socket) {
        $response = "";
        while ($line = fgets($socket, 515)) {
            $response .= $line;
            if (substr($line, 3, 1) == " ") break;
        }
        return $response;
    }
}

// Example Usage
// $hotmail = new Hotmail('your_email@hotmail.com', 'your_password', 'Your Name');
// echo $hotmail->sendEmail('recipient@example.com', 'Test Email', 'Hello, this is a test email using pure PHP SMTP.');

?>