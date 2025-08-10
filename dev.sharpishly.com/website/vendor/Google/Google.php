<?php

namespace Google;

class Google {

    private $apiKey;
    private $baseUrl;

    public function __construct($apiKey){
        $this->apiKey = $apiKey;
        $this->baseUrl = "https://www.googleapis.com";
    }

    /**
     * Search for books using the Google Books API.
     *
     * @param string $query The search query string.
     * @param int $maxResults The maximum number of results to return.
     * @return array The response data from the Google Books API.
     */
    public function searchBooks($query, $maxResults = 10){
        $url = $this->baseUrl . "/books/v1/volumes?q=" . urlencode($query) . "&maxResults=" . $maxResults . "&key=" . $this->apiKey;

        return $this->makeRequest($url);
    }

    /**
     * General method to make a request to the Google API.
     *
     * @param string $url The API endpoint URL.
     * @return array The response data.
     */
    private function makeRequest($url){
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
