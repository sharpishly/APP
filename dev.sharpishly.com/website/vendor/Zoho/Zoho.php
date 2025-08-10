<?php

namespace Zoho;

class Zoho {
    private $clientId;
    private $clientSecret;
    private $refreshToken;
    private $apiDomain;
    private $accessToken;
    
    /**
     * Constructor to initialize Zoho API credentials and domain
     * @param string $clientId Zoho API Client ID
     * @param string $clientSecret Zoho API Client Secret
     * @param string $refreshToken Zoho API Refresh Token
     * @param string $apiDomain Zoho API domain (e.g., 'https://mail.zoho.com')
     */
    public function __construct($clientId, $clientSecret, $refreshToken, $apiDomain = 'https://mail.zoho.com') {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->refreshToken = $refreshToken;
        $this->apiDomain = rtrim($apiDomain, '/');
    }

    /**
     * Generate or refresh access token using refresh token
     * @return string Access token
     * @throws Exception on failure
     */
    private function getAccessToken() {
        $url = 'https://accounts.zoho.com/oauth/v2/token';
        $params = [
            'refresh_token' => $this->refreshToken,
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'grant_type' => 'refresh_token'
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception('Failed to obtain access token: ' . $response);
        }

        $data = json_decode($response, true);
        if (isset($data['access_token'])) {
            $this->accessToken = $data['access_token'];
            return $this->accessToken;
        } else {
            throw new \Exception('Access token not found in response: ' . $response);
        }
    }

    /**
     * Send an email using Zoho Mail API
     * @param string $fromAddress Sender email address
     * @param string $toAddress Recipient email address
     * @param string $subject Email subject
     * @param string $content Email body (HTML or plain text)
     * @param string|null $ccAddress CC recipient email address (optional)
     * @param string|null $bccAddress BCC recipient email address (optional)
     * @return array Response from API
     * @throws Exception on failure
     */
    public function sendEmail($fromAddress, $toAddress, $subject, $content, $ccAddress = null, $bccAddress = null) {
        $accessToken = $this->accessToken ?? $this->getAccessToken();
        $accountId = $this->getAccountId();

        $url = "{$this->apiDomain}/api/accounts/{$accountId}/messages";
        $params = [
            'fromAddress' => $fromAddress,
            'toAddress' => $toAddress,
            'subject' => $subject,
            'content' => $content
        ];

        if ($ccAddress) {
            $params['ccAddress'] = $ccAddress;
        }
        if ($bccAddress) {
            $params['bccAddress'] = $bccAddress;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken,
            'Content-Type: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($httpCode !== 200 || isset($data['status']['code']) && $data['status']['code'] !== 200) {
            throw new \Exception('Failed to send email: ' . $response);
        }

        return $data;
    }

    /**
     * Fetch received emails from Zoho Mail
     * @param int $limit Number of emails to fetch (default: 10)
     * @param string|null $folderId Folder ID to fetch emails from (optional)
     * @return array List of emails
     * @throws Exception on failure
     */
    public function receiveEmails($limit = 10, $folderId = null) {
        $accessToken = $this->accessToken ?? $this->getAccessToken();
        $accountId = $this->getAccountId();

        $url = "{$this->apiDomain}/api/accounts/{$accountId}/messages";
        $params = ['limit' => $limit];
        if ($folderId) {
            $params['folderId'] = $folderId;
        }
        $url .= '?' . http_build_query($params);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($httpCode !== 200 || isset($data['status']['code']) && $data['status']['code'] !== 200) {
            throw new \Exception('Failed to fetch emails: ' . $response);
        }

        return $data['data'] ?? [];
    }

    /**
     * Get account ID for the authenticated user
     * @return string Account ID
     * @throws Exception on failure
     */
    private function getAccountId() {
        $accessToken = $this->accessToken ?? $this->getAccessToken();
        $url = "{$this->apiDomain}/api/accounts";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken,
            'Accept: application/json'
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);
        if ($httpCode !== 200 || !isset($data['data'][0]['accountId'])) {
            throw new \Exception('Failed to fetch account ID: ' . $response);
        }

        return $data['data'][0]['accountId'];
    }
}
?>