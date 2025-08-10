<?php

namespace TikTok;

class TikTok {
    private $username;
    private $password;

    public function __construct($username = null, $password = null) {
        if ($username && $password) {
            $this->username = $username;
            $this->password = $password;
        }
    }

    public function login() {
        // Simulating the TikTok login process
        // In a real scenario, you would make an HTTP request to the TikTok API
        // For simplicity, we will assume that the credentials are valid and return the user details

        $userDetails = [
            'email' => 'username@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'profilePictureUrl' => 'https://example.com/image.jpg'
        ];

        return $userDetails;
    }

    public function getCredentials() {
        // Returns the user's credentials (username, password)
        return [
            'username' => $this->username,
            'password' => $this->password
        ];
    }
}

?>