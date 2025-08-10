<?php

namespace FacebookSdk;

use Exception;

class FacebookSdk
{
    private string $appId;
    private string $appSecret;
    private string $redirectUri;
    private ?string $accessToken = null;

    public function __construct(array $config = [])
    {
        $this->appId = $config['app_id'] ?? '';
        $this->appSecret = $config['app_secret'] ?? '';
        $this->redirectUri = $config['redirect_uri'] ?? '';

        if (empty($this->appId) || empty($this->appSecret) || empty($this->redirectUri)) {
            throw new Exception('Facebook App ID, App Secret, and Redirect URI must be provided.');
        }
    }

    public function getLoginUrl(array $scope = ['email', 'public_profile']): string
    {
        $params = [
            'client_id' => $this->appId,
            'redirect_uri' => $this->redirectUri,
            'scope' => implode(',', $scope),
            'response_type' => 'code',
            'auth_type' => 'rerequest',
        ];

        return 'https://www.facebook.com/v19.0/dialog/oauth?' . http_build_query($params);
    }

    public function getAccessToken(string $code): array
    {
        $url = 'https://graph.facebook.com/v19.0/oauth/access_token';
        $params = [
            'client_id' => $this->appId,
            'client_secret' => $this->appSecret,
            'redirect_uri' => $this->redirectUri,
            'code' => $code,
        ];

        $responseData = $this->makeGetRequest($url, $params);

        if (!isset($responseData['access_token'])) {
            throw new Exception('Failed to retrieve access token from Facebook.');
        }

        $this->accessToken = $responseData['access_token'];
        return $responseData;
    }

    public function getUserData(array $fields = ['id', 'name', 'email']): array
    {
        if (!$this->accessToken) {
            throw new Exception('Access token is not set. Call getAccessToken() first.');
        }

        $url = 'https://graph.facebook.com/v19.0/me';
        $params = [
            'access_token' => $this->accessToken,
            'fields' => implode(',', $fields),
        ];

        return $this->makeGetRequest($url, $params);
    }

    public function debugToken(string $inputToken): array
    {
        $url = 'https://graph.facebook.com/debug_token';
        $params = [
            'input_token' => $inputToken,
            'access_token' => "{$this->appId}|{$this->appSecret}",
        ];

        return $this->makeGetRequest($url, $params);
    }

    public function setCredentials(array $config): void
    {
        $this->appId = $config['app_id'] ?? $this->appId;
        $this->appSecret = $config['app_secret'] ?? $this->appSecret;
        $this->redirectUri = $config['redirect_uri'] ?? $this->redirectUri;
    }

    private function makeGetRequest(string $url, array $params): array
    {
        $fullUrl = $url . '?' . http_build_query($params);
        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $fullUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_SSL_VERIFYHOST => 2,
        ]);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            throw new Exception("cURL Error: $error");
        }

        curl_close($ch);
        $data = json_decode($response, true);

        if (isset($data['error'])) {
            $message = "Facebook API Error: {$data['error']['message']} (Code: {$data['error']['code']})";
            throw new Exception($message);
        }

        return $data;
    }
}
