<?php

namespace SimpleEmail;

/**
 * Class SimpleEmail
 *
 * A simple PHP class for sending emails without relying on external libraries.
 * It uses PHP's built-in mail() function.
 */
class SimpleEmail {

    /**
     * @var string The recipient(s) of the email. Can be a single email address or a comma-separated list.
     */
    private $to;

    /**
     * @var string The subject of the email.
     */
    private $subject;

    /**
     * @var string The body/message content of the email.
     */
    private $message;

    /**
     * @var string Additional headers for the email, such as From, Cc, Bcc, Content-Type, etc.
     * Each header should be separated by CRLF (\r\n).
     */
    private $headers;

    /**
     * @var string Additional parameters to pass to the sendmail program.
     */
    private $parameters;

    /**
     * Constructor for the SimpleEmail class.
     *
     * @param string $to The recipient(s) email address(es).
     * @param string $subject The subject of the email.
     * @param string $message The body of the email.
     * @param string $headers Optional additional headers.
     * @param string $parameters Optional additional parameters for sendmail.
     */
    public function __construct($to = '', $subject = '', $message = '', $headers = '', $parameters = '') {
        $this->to = $to;
        $this->subject = $subject;
        $this->message = $message;
        $this->headers = $headers;
        $this->parameters = $parameters;
    }

    /**
     * Sets the recipient(s) of the email.
     *
     * @param string $to A single email address or a comma-separated list of addresses.
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function setTo($to) {
        $this->to = $to;
        return $this;
    }

    /**
     * Gets the current recipient(s).
     *
     * @return string
     */
    public function getTo() {
        return $this->to;
    }

    /**
     * Sets the subject of the email.
     *
     * @param string $subject The subject string.
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function setSubject($subject) {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Gets the current subject.
     *
     * @return string
     */
    public function getSubject() {
        return $this->subject;
    }

    /**
     * Sets the message body of the email.
     *
     * @param string $message The message body string.
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    /**
     * Gets the current message body.
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Sets additional headers for the email.
     *
     * Example: "From: sender@example.com\r\nReply-To: reply@example.com\r\nContent-Type: text/html; charset=UTF-8"
     *
     * @param string $headers The headers string.
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function setHeaders($headers) {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Gets the current headers.
     *
     * @return string
     */
    public function getHeaders() {
        return $this->headers;
    }

    /**
     * Sets additional parameters for the sendmail program.
     *
     * @param string $parameters The parameters string.
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function setParameters($parameters) {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Gets the current parameters.
     *
     * @return string
     */
    public function getParameters() {
        return $this->parameters;
    }

    /**
     * Adds a single header to the existing headers.
     *
     * @param string $header The header line (e.g., "From: sender@example.com").
     * @return SimpleEmail Returns the current instance for method chaining.
     */
    public function addHeader($header) {
        if (!empty($this->headers)) {
            $this->headers .= "\r\n";
        }
        $this->headers .= $header;
        return $this;
    }

    /**
     * Validates an email address.
     *
     * @param string $email The email address to validate.
     * @return bool True if the email is valid, false otherwise.
     */
    public function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Sends the email using PHP's built-in mail() function.
     *
     * @return bool True on success, false on failure.
     */
    public function send() {
        // Basic validation for 'to' address
        if (empty($this->to)) {
            error_log("SimpleEmail Error: Recipient 'to' address is empty.");
            return false;
        }

        // You might want more robust validation for multiple recipients here
        // For simplicity, we'll just check if the first one is valid if it's a comma-separated list
        $recipients = explode(',', $this->to);
        if (!empty($recipients) && !$this->isValidEmail(trim($recipients[0]))) {
            error_log("SimpleEmail Error: Invalid recipient email address: " . trim($recipients[0]));
            return false;
        }

        // Attempt to send the email
        // The mail() function returns true on success, false on failure.
        // However, it does not indicate if the email was actually delivered.
        // Delivery status depends on the mail server configuration.
        $success = mail($this->to, $this->subject, $this->message, $this->headers, $this->parameters);

        if (!$success) {
            error_log("SimpleEmail Error: Failed to send email to " . $this->to);
        }

        return $success;
    }
}
?>
