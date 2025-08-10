<?php

namespace Shopify;

class Shopify {

    private $apiKey;
    private $apiSecret;
    private $shopName;
    private $apiVersion = "2023-07"; //  Set your desired API version

    public function __construct($apiKey, $apiSecret, $shopName) {
        $this->apiKey = $apiKey;
        $this->apiSecret = $apiSecret;
        $this->shopName = $shopName;
    }

    private function makeRequest($method, $endpoint, $data = null) {
        $url = "https://" . $this->shopName . ".myshopify.com/admin/api/" . $this->apiVersion . "/" . $endpoint . ".json";

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "X-Shopify-Access-Token: " . $this->apiKey,  // Use the Access Token for authentication
                "Content-Type: application/json"
            ],
            CURLOPT_CUSTOMREQUEST => $method,
        ]);

        if ($data) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code

        if ($httpCode >= 400) { // Check for API errors (4xx or 5xx)
          $errorData = json_decode($response, true);
          $errorMessage = isset($errorData['errors']) ? print_r($errorData['errors'], true) : "HTTP Error: " . $httpCode . " - " . $response; // Extract error message
          throw new \Exception($errorMessage);
        }


        return json_decode($response, true); // Decode JSON response
    }


    // Example Shopify API Methods:

    public function getProducts() {
        return $this->makeRequest("GET", "products");
    }

    public function createProduct($productData) { // Example $productData: see Shopify API docs
        return $this->makeRequest("POST", "products", $productData);
    }

    public function getProduct($productId) {
        return $this->makeRequest("GET", "products/" . $productId);
    }

    public function updateProduct($productId, $productData) {
        return $this->makeRequest("PUT", "products/" . $productId, $productData);
    }

    public function deleteProduct($productId) {
        return $this->makeRequest("DELETE", "products/" . $productId);
    }

    // ... Add other Shopify API methods as needed ...

}



?>