<?php

class Twitter {
    private $api_key;
    private $api_secret;
    private $access_token;
    private $access_token_secret;

    public function __construct($api_key, $api_secret, $access_token, $access_token_secret) {
        $this->api_key = $api_key;
        $this->api_secret = $api_secret;
        $this->access_token = $access_token;
        $this->access_token_secret = $access_token_secret;
    }

    /**
     * Generate OAuth signature for API calls
     */
    private function generateOAuthSignature($url, $method, $params) {
        $oauth = [
            'oauth_consumer_key' => $this->api_key,
            'oauth_nonce' => bin2hex(random_bytes(16)), // More secure nonce
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_token' => $this->access_token,
            'oauth_version' => '1.0'
        ];

        $params = array_merge($params, $oauth);
        ksort($params);

        $base_string = strtoupper($method) . '&' . rawurlencode($url) . '&' . rawurlencode(http_build_query($params, '', '&', PHP_QUERY_RFC3986));
        $signing_key = rawurlencode($this->api_secret) . '&' . rawurlencode($this->access_token_secret);

        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_string, $signing_key, true));
        return $oauth;
    }

    /**
     * Build OAuth Authorization Header
     */
    private function buildOAuthHeader($oauth) {
        $header = 'Authorization: OAuth ';
        $values = [];

        foreach ($oauth as $key => $value) {
            $values[] = rawurlencode($key) . '="' . rawurlencode($value) . '"';
        }

        return $header . implode(', ', $values);
    }

    /**
     * Perform an API call to X/Twitter
     * @param string $url The endpoint URL
     * @param string $method HTTP method (GET, POST, etc.)
     * @param array $params Additional parameters for the request
     * @return array|null Decoded JSON response from the API
     */
    public function apiCall($url, $method = 'GET', $params = []) {
        $oauth = $this->generateOAuthSignature($url, $method, $params);
        $header = [$this->buildOAuthHeader($oauth)];

        $ch = curl_init();
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        } else {
            $url .= '?' . http_build_query($params);
            curl_setopt($ch, CURLOPT_URL, $url);
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['error' => $error];
        }

        return json_decode($response, true);
    }

    /**
     * Fetch user timeline tweets
     * @param string $screen_name The screen name of the user
     * @return array|null
     */
    public function getUserTimeline($screen_name) {
        $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
        $params = ['screen_name' => $screen_name, 'count' => 20];
        return $this->apiCall($url, 'GET', $params);
    }
}

// Example usage:
// $twitter = new Twitter('YOUR_API_KEY', 'YOUR_API_SECRET', 'YOUR_ACCESS_TOKEN', 'YOUR_ACCESS_TOKEN_SECRET');
// $tweets = $twitter->getUserTimeline('example_user');
// var_dump($tweets);
?>
