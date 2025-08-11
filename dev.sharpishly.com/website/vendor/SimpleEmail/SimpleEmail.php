<?php

namespace SimpleEmail;

/**
 * Class SimpleEmail
 *
 * A simple PHP class for sending emails without external libraries.
 */
class SimpleEmail
{
    private string $to = '';
    private string $subject = '';
    private string $message = '';
    private string $headers = '';
    private string $parameters = '';
    private bool $isHtml = false;
    private array $attachments = [];

    public function __construct(
        string $to = '',
        string $subject = '',
        string $message = '',
        string $from = '',
        bool $isHtml = false
    ) {
        $this->setTo($to);
        $this->setSubject($subject);
        $this->setMessage($message);
        $this->setHtml($isHtml);

        if ($from) {
            $this->addHeader("From: " . $this->sanitizeEmail($from));
        }
        $this->addHeader("MIME-Version: 1.0");
    }

    private function sanitizeEmail(string $email): string
    {
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    private function sanitizeHeader(string $header): string
    {
        return preg_replace('/[\r\n]+/', '', trim($header));
    }

    private function sanitizeSubject(string $subject): string
    {
        return $this->sanitizeHeader($subject);
    }

    public function setTo(string $to): self
    {
        $this->to = $this->sanitizeEmail($to);
        return $this;
    }

    public function setSubject(string $subject): self
    {
        $this->subject = $this->sanitizeSubject($subject);
        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function setHeaders(string $headers): self
    {
        $this->headers = trim($headers);
        return $this;
    }

    public function setParameters(string $parameters): self
    {
        $this->parameters = $parameters;
        return $this;
    }

    public function setHtml(bool $isHtml = true): self
    {
        $this->isHtml = $isHtml;
        return $this;
    }

    public function addHeader(string $header): self
    {
        $header = $this->sanitizeHeader($header);
        if (stripos($this->headers, $header) === false) {
            $this->headers .= (empty($this->headers) ? '' : "\r\n") . $header;
        }
        return $this;
    }

    public function addAttachment(string $filePath, string $fileName = ''): self
    {
        if (!is_readable($filePath)) {
            error_log("SimpleEmail Error: Cannot read attachment file: $filePath");
            return $this;
        }

        $content = base64_encode(file_get_contents($filePath));
        $fileName = $fileName ?: basename($filePath);

        $this->attachments[] = [
            'name' => htmlspecialchars($this->sanitizeHeader($fileName), ENT_QUOTES, 'UTF-8'),
            'content' => $content,
            'type' => mime_content_type($filePath)
        ];

        return $this;
    }

    public function isValidEmail(string $email): bool
    {
        $emails = array_map('trim', explode(',', $email));
        foreach ($emails as $e) {
            if (!filter_var($e, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
        }
        return true;
    }

    private function buildMessageBody(): string
    {
        if (empty($this->attachments)) {
            return $this->message;
        }

        $boundary = md5(uniqid(time()));
        $body = "--$boundary\r\n";
        $body .= $this->isHtml 
            ? "Content-Type: text/html; charset=UTF-8\r\n"
            : "Content-Type: text/plain; charset=UTF-8\r\n";
        $body .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $body .= $this->message . "\r\n";

        foreach ($this->attachments as $attachment) {
            $body .= "--$boundary\r\n";
            $body .= "Content-Type: {$attachment['type']}; name=\"{$attachment['name']}\"\r\n";
            $body .= "Content-Transfer-Encoding: base64\r\n";
            $body .= "Content-Disposition: attachment; filename=\"{$attachment['name']}\"\r\n\r\n";
            $body .= chunk_split($attachment['content']) . "\r\n";
        }

        $body .= "--$boundary--";

        // Add boundary header
        $this->addHeader("Content-Type: multipart/mixed; boundary=\"$boundary\"");

        return $body;
    }

    private function buildHeaders(): string
    {
        if (empty($this->attachments)) {
            $this->addHeader(
                $this->isHtml
                    ? "Content-Type: text/html; charset=UTF-8"
                    : "Content-Type: text/plain; charset=UTF-8"
            );
        }
        return $this->headers;
    }

    public function send(): bool
    {
        try {
            if (empty($this->to)) {
                throw new \InvalidArgumentException("Recipient email address is missing.");
            }

            if (!$this->isValidEmail($this->to)) {
                throw new \InvalidArgumentException("Invalid email address: {$this->to}");
            }

            if (empty($this->subject)) {
                throw new \InvalidArgumentException("Email subject is missing.");
            }

            if (empty($this->message)) {
                throw new \InvalidArgumentException("Email message body is missing.");
            }

            if (empty($this->headers)) {
                throw new \InvalidArgumentException("Headers are missing. A From header is required.");
            }

            $body = $this->buildMessageBody();
            $headers = $this->buildHeaders();

            $success = mail($this->to, $this->subject, $body, $headers, $this->parameters);

            if (!$success) {
                throw new \RuntimeException("mail() function failed.");
            }

            return true;
        } catch (\Throwable $e) {
            error_log("[SimpleEmail Error] " . $e->getMessage());
            return false;
        }
    }
}
?>
