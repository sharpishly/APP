<?php

namespace GraphQlClient;

class GraphQLClient {

    private $endpoint;
    private $headers;

    public function __construct($endpoint, $headers = []) {
        $this->endpoint = $endpoint;
        $this->headers = $headers;
    }

    public function executeQuery($query, $variables = []) {
        $data = [
            'query' => $query,
            'variables' => $variables,
        ];

        $jsonData = json_encode($data);

        $ch = curl_init($this->endpoint);

        $defaultHeaders = [
            'Content-Type: application/json',
            'Accept: application/json',
        ];

        $allHeaders = array_merge($defaultHeaders, $this->headers); // Combine default and custom headers

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $allHeaders,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonData,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 400) { // Check for HTTP errors
          $errorData = json_decode($response, true);
          $errorMessage = isset($errorData['errors']) ? print_r($errorData['errors'], true) : "HTTP Error: " . $httpCode . " - " . $response;
          throw new \Exception($errorMessage);
        }

        return json_decode($response, true); // Decode JSON response
    }
}



?>