<?php

namespace LinkedIn\LinkedIn;

class LinkedIn {
    private $clientId;
    private $clientSecret;
    private $redirectUri;
    private $accessToken;
    private $baseApiUrl = 'https://api.linkedin.com/v2/';
    private $authUrl = 'https://www.linkedin.com/oauth/v2/';

    /**
     * Constructor to initialize LinkedIn API credentials
     * @param string $clientId LinkedIn App Client ID
     * @param string $clientSecret LinkedIn App Client Secret
     * @param string $redirectUri Redirect URI registered in LinkedIn Developer Portal
     */
    public function __construct($clientId, $clientSecret, $redirectUri) {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->redirectUri = $redirectUri;
    }

    /**
     * Get LinkedIn authorization URL for user login
     * @param array $scopes Permissions to request
     * @return string Authorization URL
     */
    public function getLoginUrl($scopes = ['r_liteprofile', 'r_emailaddress', 'w_member_social']) {
        $params = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(' ', $scopes),
            'state' => bin2hex(random_bytes(16)) // CSRF protection
        ];
        return $this->authUrl . 'authorization?' . http_build_query($params);
    }

    /**
     * Exchange authorization code for access token
     * @param string $code Authorization code from LinkedIn
     * @return array|bool Access token data or false on failure
     */
    public function getAccessToken($code) {
        $url = $this->authUrl . 'accessToken';
        $data = [
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => $this->redirectUri,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret
        ];

        $response = $this->makePostRequest($url, $data);
        if ($response && isset($response['access_token'])) {
            $this->accessToken = $response['access_token'];
            return $response;
        }
        return false;
    }

    /**
     * Register a new user on LinkedIn
     * @param array $userData User data (firstName, lastName, email, password)
     * @return array|bool Registration response or false on failure
     */
    public function registerUser($userData) {
        // LinkedIn's API doesn't support direct user registration.
        // This is a placeholder for a potential future endpoint or manual process.
        // Typically, users must register via LinkedIn's website, and the API handles authentication.
        // Here, we'll simulate registration by redirecting to the OAuth flow if needed.
        if (!$this->accessToken) {
            return ['error' => 'User must authenticate via OAuth. Use getLoginUrl() to start the process.'];
        }
        return ['message' => 'User registration not directly supported by LinkedIn API. Use OAuth flow.'];
    }

    /**
     * Retrieve latest job postings
     * @param int $limit Number of jobs to retrieve
     * @return array|bool Job listings or false on failure
     */
    public function getLatestJobs($limit = 10) {
        if (!$this->accessToken) {
            return ['error' => 'Access token required. Authenticate first.'];
        }

        // Note: Job search API may require Talent Solutions partner access
        $url = $this->baseApiUrl . 'jobs?perPage=' . $limit;
        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Accept: application/json'
        ];

        $response = $this->makeGetRequest($url, $headers);
        if ($response && isset($response['elements'])) {
            return $response['elements'];
        }
        return $response ?: ['error' => 'Failed to retrieve jobs'];
    }

    /**
     * Apply for a job on behalf of the user
     * @param string $jobId Job ID to apply for
     * @param array $applicationData Application data (e.g., resume, cover letter)
     * @return array|bool Application response or false on failure
     */
    public function applyForJob($jobId, $applicationData) {
        if (!$this->accessToken) {
            return ['error' => 'Access token required. Authenticate first.'];
        }

        // Note: Job application API requires specific permissions (Talent Solutions).
        // This is a simplified example; actual endpoint may vary.
        $url = $this->baseApiUrl . 'jobApplications';
        $headers = [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json',
            'Accept: application/json'
        ];

        $payload = array_merge(['jobId' => $jobId], $applicationData);
        $response = $this->makePostRequest($url, $payload, $headers);
        return $response ?: ['error' => 'Failed to submit job application'];
    }

    /**
     * Make a GET request to the LinkedIn API
     * @param string $url API endpoint
     * @param array $headers HTTP headers
     * @return array|bool Decoded JSON response or false on failure
     */
    private function makeGetRequest($url, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }
        return ['error' => 'HTTP error: ' . $httpCode, 'response' => $response];
    }

    /**
     * Make a POST request to the LinkedIn API
     * @param string $url API endpoint
     * @param array $data POST data
     * @param array $headers HTTP headers
     * @return array|bool Decoded JSON response or false on failure
     */
    private function makePostRequest($url, $data, $headers = []) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array_merge([
            'Content-Type: application/json',
            'Accept: application/json'
        ], $headers));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }
        return ['error' => 'HTTP error: ' . $httpCode, 'response' => $response];
    }
}

?>