<?php

namespace UserStorage;

use Session\Session;
use App\Core\Db; // Include your Db class

class UserStorage {
    private $accessToken;
    private $refreshToken;
    private $userInfo;
    private $sessionKey = 'google_user_info';
    private $session;
    private $db;
    private $currentUserId = 1; // Replace with your actual way of getting the current user ID

    public function __construct(Session $session, Db $db) {
        $this->session = $session;
        $this->db = $db;

        // Try to load user info from session
        $sessionUserInfo = $this->session->get($this->sessionKey);
        if ($sessionUserInfo) {
            $this->userInfo = $sessionUserInfo;
            $this->accessToken = $this->userInfo['access_token'] ?? null;
            $this->refreshToken = $this->userInfo['refresh_token'] ?? null;
        } else {
            // If not in session, try to load from database
            $this->loadUserInfoFromDatabase();
        }
    }

    private function loadUserInfoFromDatabase() {
        if ($this->currentUserId) {
            $result = $this->db->find([
                'table' => 'migrate_user_tokens',
                'where' => ['col' => 'user_id', 'val' => $this->currentUserId],
            ]);

            if (!empty($result['result'][0])) {
                $userData = $result['result'][0];
                $this->accessToken = $userData['access_token'];
                $this->refreshToken = $userData['refresh_token'];
                $this->userInfo = $this->session->get($this->sessionKey) ?? []; // Keep existing session info if any
                $this->userInfo['access_token'] = $this->accessToken;
                $this->userInfo['refresh_token'] = $this->refreshToken;
                $this->session->set($this->sessionKey, $this->userInfo);
            }
        }
    }

    // Store user info in session and database
    public function storeUserInfo($accessToken, $refreshToken, $userInfo, $expiryTime = null) {
        $this->accessToken = $accessToken;
        $this->refreshToken = $refreshToken;
        $this->userInfo = $userInfo;
        $this->session->set($this->sessionKey, [
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'email' => $userInfo['email'],
            'first_name' => $userInfo['first_name'],
            'last_name' => $userInfo['last_name'],
            // 'picture' => $userInfo['picture'],
        ]);
        // Set cookies for persistence (expire in 30 days)
        setcookie('google_user_info', json_encode($this->userInfo), time() + 60 * 60 * 24 * 30, '/');

        // Save to database
        if ($this->currentUserId) {
            $saveData = [
                'user_id' => $this->currentUserId,
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'created_at' => date('Y-m-d H:i:s'), // Record creation time
            ];
            if ($expiryTime) {
                $saveData['expiry_time'] = date('Y-m-d H:i:s', $expiryTime); // Assuming $expiryTime is a Unix timestamp
            }

            // $this->db->save([
            //     'table' => 'migrate_user_tokens',
            //     'save' => $saveData,
            // ]);
        }
    }

    // Check if the user is logged in
    public function isLoggedIn() {
        return isset($this->userInfo) && isset($this->userInfo['email']);
    }

    // Get the user's email
    public function getEmail() {
        return $this->userInfo['email'] ?? null;
    }

    // Get the user's first name
    public function getFirstName() {
        return $this->userInfo['first_name'] ?? null;
    }

    // Get the user's last name
    public function getLastName() {
        return $this->userInfo['last_name'] ?? null;
    }

    // Get the user's profile picture URL
    public function getProfilePicture() {
        return $this->userInfo['picture'] ?? null;
    }

    // Get access token
    public function getAccessToken() {
        // First check in the property, then it's loaded from session or DB in constructor
        return $this->accessToken;
    }

    // Get refresh token
    public function getRefreshToken() {
        return $this->refreshToken;
    }

    // Clear user data (logout)
    public function clearUserData() {
        $this->session->set($this->sessionKey, null);
        setcookie('google_user_info', '', time() - 3600, '/'); // Expire cookie
        $this->userInfo = null;
        $this->accessToken = null;
        $this->refreshToken = null;
        if ($this->currentUserId) {
            // Optionally clear from database as well
            // $this->db->update(['table' => 'migrate_user_tokens', 'update' => ['access_token' => null, 'refresh_token' => null], 'where' => ['col' => 'user_id', 'val' => $this->currentUserId]]);
        }
    }

    // Refresh the access token if it's expired
    public function refreshAccessToken($clientId, $clientSecret) {
        if (!$this->refreshToken) {
            throw new \Exception("No refresh token available.");
        }
        $url = "https://oauth2.googleapis.com/token";
        $data = [
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token',
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/x-www-form-urlencoded"]);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Request Error: ' . curl_error($ch));
        }
        curl_close($ch);
        $responseData = json_decode($response, true);
        if (isset($responseData['access_token'])) {
            // Update the access token
            $this->accessToken = $responseData['access_token'];
            $userInfo = $this->session->get($this->sessionKey);
            $userInfo['access_token'] = $this->accessToken;
            $this->session->set($this->sessionKey, $userInfo);
            if ($this->currentUserId) {
                $this->db->update([
                    'table' => 'migrate_user_tokens',
                    'update' => ['access_token' => $this->accessToken],
                    'where' => ['col' => 'user_id', 'val' => $this->currentUserId],
                ]);
            }
            return $this->accessToken;
        } else {
            throw new \Exception("Failed to refresh access token.");
        }
    }

    // Store additional user data (if needed)
    public function storeAdditionalUserInfo($additionalInfo) {
        $userInfo = $this->session->get($this->sessionKey);
        $userInfo['additional_info'] = $additionalInfo;
        $this->session->set($this->sessionKey, $userInfo);
        $this->userInfo['additional_info'] = $additionalInfo;
        // Update cookies with new data
        setcookie('google_user_info', json_encode($this->userInfo), time() + 60 * 60 * 24 * 30, '/');
    }
}