<?php

namespace WhatsApp;

class WhatsApp {
    private $username;
    private $password;

    public function __construct($username = null, $password = null) {
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Simulates a login into a website using TikTok credentials
     *
     * @return array User info if login is successful, otherwise an error message
     */
    public function login() {
        // For demonstration purposes only. In reality, you should handle errors properly.
        if (empty($this->username) || empty($this->password)) {
            return ['error' => 'Username and password are required'];
        }

        // Simulate API call to TikTok
        $response = json_decode(json_encode([
            'status' => true,
            'data' => [
                'id' => 12345,
                'username' => $this->username,
                'email' => 'user@example.com',
                'profile_picture' => 'https://example.com/picture.jpg'
            ]
        ]), true);

        // If API call is successful
        if ($response['status']) {
            return ['success' => true, 'data' => $response['data']];
        } else {
            return ['error' => 'Invalid username or password'];
        }
    }

    /**
     * Gets user info from the TikTok API using the provided credentials.
     *
     * @return array User info if login is successful, otherwise an error message
     */
    public function getUserInfo() {
        $loginResponse = $this->login();

        // If login was successful
        if ($loginResponse['success']) {
            return $loginResponse['data'];
        } else {
            return ['error' => 'Failed to get user info'];
        }
    }
}

?>