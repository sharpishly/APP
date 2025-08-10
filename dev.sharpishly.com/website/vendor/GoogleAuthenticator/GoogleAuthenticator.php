<?php

namespace GoogleAuthenticator;

use Session\Session; // Make sure this is correctly aliased/used

class GoogleAuthenticator {

    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $session; // The injected Session instance

    // You don't need to store accessToken/refreshToken as properties
    // if you always fetch them from the session via the Session object.
    // This simplifies state management, as the session is the single source of truth.

    public function __construct($clientId, $clientSecret, $redirectUri, Session $session) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
        $this->session = $session;

        // No need to set $this->accessToken / $this->refreshToken here from session
        // because you will always get them via $this->session->get('google_access_token') etc.
    }

    // ... (Your login method remains the same) ...
    // --- ADD THIS NEW METHOD ---
    public function login() {
        // Define the scope of user data you want to access
        // 'openid' is for basic user identification
        // 'profile' for name, picture etc.
        // 'email' for email address
        $scope = 'https://www.googleapis.com/auth/userinfo.profile https://www.googleapis.com/auth/userinfo.email';

        // Build the authorization URL
        $authUrl = 'https://accounts.google.com/o/oauth2/auth?' . http_build_query([
            'client_id'     => $this->clientId,
            'redirect_uri'  => $this->redirectUri,
            'response_type' => 'code',
            'scope'         => $scope,
            'access_type'   => 'offline', // Request a refresh token for long-lived access
            'prompt'        => 'consent', // Force user to re-consent, ensures refresh token is always granted
        ]);

        // Redirect the user to Google's authentication page
        header('Location: ' . $authUrl);
        exit();
    }
    // --- END NEW METHOD ---
    
    public function handleCallback($queryParams) {
        error_log("GoogleAuthenticator: handleCallback called. Query Params: " . json_encode($queryParams));

        // Ensure you have the 'code' parameter
        if (!isset($queryParams['code'])) {
            error_log("GoogleAuthenticator Error: 'code' parameter missing in callback.");
            throw new \Exception("Missing authorization code.");
        }

        $authCode = $queryParams['code'];
        error_log("GoogleAuthenticator: Authorization Code: " . $authCode);

        // Prepare data for token exchange
        $data = [
            'code'          => $authCode,
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri'  => $this->redirectUri,
            'grant_type'    => 'authorization_code',
        ];

        // Send POST request to Google's token endpoint
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        error_log("GoogleAuthenticator: Raw token response from Google: " . $response);
        error_log("GoogleAuthenticator: HTTP Code from token endpoint: " . $httpCode);

        if ($httpCode !== 200) {
            error_log("GoogleAuthenticator Error: Token exchange failed with HTTP code " . $httpCode . " Response: " . $response);
            throw new \Exception("Failed to retrieve access token: " . $response);
        }

        $tokenResponse = json_decode($response, true);

        if (!isset($tokenResponse['access_token'])) {
            error_log("GoogleAuthenticator Error: Access token not found in response: " . print_r($tokenResponse, true));
            throw new \Exception("Access token not found in Google's response.");
        }

        // --- THE CRUCIAL CHANGE HERE ---
        // Store tokens using the Session object, under specific keys
        $this->session->set('google_access_token', $tokenResponse['access_token']);
        if (isset($tokenResponse['refresh_token'])) {
            $this->session->set('google_refresh_token', $tokenResponse['refresh_token']);
        }
        // Also set a flag that the user is logged in via Google if you use it elsewhere
        $this->session->set('google_user_logged_in', true); // Or whatever flag you need
        // --- END CRUCIAL CHANGE ---

        error_log("GoogleAuthenticator: Access token successfully stored.");
    }

    private function checkAccessToken() {
        // --- THE CRUCIAL CHANGE HERE ---
        // Retrieve tokens using the Session object
        $accessToken = $this->session->get('google_access_token');
        $refreshToken = $this->session->get('google_refresh_token');

        error_log("GoogleAuthenticator: checkAccessToken called. Current accessToken state: " . (empty($accessToken) ? 'empty' : 'present'));
        error_log("GoogleAuthenticator: Current refreshToken state: " . (empty($refreshToken) ? 'empty' : 'present'));

        if (empty($accessToken)) {
            if (!empty($refreshToken)) {
                error_log("GoogleAuthenticator: Access token expired, attempting to refresh using refresh token.");
                // Implement your token refresh logic here
                try {
                    $this->refreshAccessToken($refreshToken);
                    // After successful refresh, accessToken will be updated in session,
                    // so we need to fetch it again.
                    $accessToken = $this->session->get('google_access_token');
                } catch (\Exception $e) {
                    error_log("GoogleAuthenticator Error: Failed to refresh access token: " . $e->getMessage());
                    throw new \Exception("Failed to refresh access token. Please log in again.");
                }
            } else {
                throw new \Exception("No access token and no refresh token available. User needs to log in.");
            }
        }

        if (empty($accessToken)) { // Re-check after potential refresh
            throw new \Exception("No access token available. Please log in again.");
        }
    }

    // Your getUserInfo, createCsvOnDrive, etc., methods should now get the access token like this:
    public function getUserInfo() {
        $this->checkAccessToken(); // Ensure tokens are valid/refreshed

        $accessToken = $this->session->get('google_access_token'); // Get the live token from session

        if (empty($accessToken)) {
            throw new \Exception("Cannot get user info: Access token is missing after check.");
        }
        
        // ... (rest of your getUserInfo logic using $accessToken)
        // Example:
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/oauth2/v2/userinfo');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("GoogleAuthenticator Error: User info fetch failed with HTTP code " . $httpCode . " Response: " . $response);
            throw new \Exception("Failed to fetch user info: " . $response);
        }

        $userInfo = json_decode($response, true);
        error_log("GoogleAuthenticator: Fetched User Info: " . print_r($userInfo, true));
        return $userInfo;
    }

    // You will need to implement this if you want token refreshing
    private function refreshAccessToken($refreshToken) {
        $data = [
            'client_id'     => $this->clientId,
            'client_secret' => $this->clientSecret,
            'refresh_token' => $refreshToken,
            'grant_type'    => 'refresh_token',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            error_log("GoogleAuthenticator Error: Token refresh failed with HTTP code " . $httpCode . " Response: " . $response);
            throw new \Exception("Failed to refresh token: " . $response);
        }

        $tokenResponse = json_decode($response, true);
        if (!isset($tokenResponse['access_token'])) {
            error_log("GoogleAuthenticator Error: No new access token in refresh response: " . print_r($tokenResponse, true));
            throw new \Exception("New access token not found after refresh.");
        }

        // Update the session with the new access token
        $this->session->set('google_access_token', $tokenResponse['access_token']);
        error_log("GoogleAuthenticator: Access token refreshed and stored.");

        // A refresh token might also be returned, usually only if the original one was revoked or a new one is issued
        if (isset($tokenResponse['refresh_token'])) {
             $this->session->set('google_refresh_token', $tokenResponse['refresh_token']);
             error_log("GoogleAuthenticator: New refresh token received and stored.");
        }
    }
}