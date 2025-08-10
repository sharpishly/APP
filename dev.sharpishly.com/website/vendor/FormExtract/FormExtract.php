<?php

namespace FormExtract;

class FormExtract
{
    private $url;
    private $formAction;
    private $formMethod;
    private $formFields = [];

    public function __construct($url)
    {
        $this->url = $url;
        $this->extractFormDetails();
    }

    private function extractFormDetails()
    {
        // Fetch the HTML from the URL
        $html = $this->fetchHtml($this->url);

        // Parse the HTML using DOMDocument
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true); // Suppress parsing errors
        $dom->loadHTML($html);
        libxml_clear_errors();

        // Find the form element
        $form = $dom->getElementsByTagName('form')->item(0);
        if (!$form) {
            throw new \Exception("No form found on the provided URL");
        }

        // Extract form action and method
        $this->formAction = $form->getAttribute('action');
        $this->formMethod = strtolower($form->getAttribute('method') ?: 'get');

        // Extract form fields
        $inputs = $form->getElementsByTagName('input');
        foreach ($inputs as $input) {
            $name = $input->getAttribute('name');
            if ($name) {
                $this->formFields[$name] = $input->getAttribute('value');
            }
        }
    }

    private function fetchHtml($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        $html = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Failed to fetch URL: ' . curl_error($ch));
        }

        curl_close($ch);
        return $html;
    }

    public function getArgs()
    {
        return $this->formFields;
    }

    public function send($args)
    {
        $postData = http_build_query($args);
        $url = $this->resolveUrl($this->url, $this->formAction);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($this->formMethod === 'post') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        } else {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . $postData);
        }

        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Failed to send form: ' . curl_error($ch));
        }

        curl_close($ch);
        return $response;
    }

    private function resolveUrl($base, $relative)
    {
        if (parse_url($relative, PHP_URL_SCHEME) != '') {
            return $relative;
        }

        if ($relative[0] === '/') {
            $parsedBase = parse_url($base);
            return $parsedBase['scheme'] . '://' . $parsedBase['host'] . $relative;
        }

        return rtrim($base, '/') . '/' . ltrim($relative, '/');
    }
}

// Example usage:
// $form = new FormExtract('https://www.example.com');
// $args = $form->getArgs();
// $args['firstname'] = 'Smith';
// $response = $form->send($args);
// echo $response;
?>