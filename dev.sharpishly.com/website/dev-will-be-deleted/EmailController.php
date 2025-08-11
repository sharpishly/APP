<?php

namespace SimpleEmail;

use SimpleEmail\SimpleEmail;

class EmailController
{
    public function form(): void
    {
        include __DIR__ . '/../views/email_form.php';
    }

    public function send(): void
    {
        $to = $_POST['to'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';
        $from = $_POST['from'] ?? '';
        $isHtml = isset($_POST['is_html']);

        $email = new SimpleEmail($to, $subject, $message, $from, $isHtml);

        // Optional file attachment
        if (!empty($_FILES['attachment']['tmp_name'])) {
            $email->addAttachment($_FILES['attachment']['tmp_name'], $_FILES['attachment']['name']);
        }

        $success = $email->send();

        if ($success) {
            echo "<p>Email sent successfully!</p>";
        } else {
            echo "<p>Failed to send email. Check error log.</p>";
        }

        echo '<p><a href="?action=form">Go back</a></p>';
    }
}
