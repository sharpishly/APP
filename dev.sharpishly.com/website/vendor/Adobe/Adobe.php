<?php

namespace Adobe;

class AdobeSign {
    private $baseUrl = 'https://api.na1.adobesign.com/api/rest/v6';
    private $accessToken;

    /**
     * Constructor for AdobeSign class
     * @param string $accessToken OAuth2 access token for authentication
     */
    public function __construct($accessToken) {
        $this->accessToken = $accessToken;
    }

    /**
     * Make an API request to Adobe Sign
     * @param string $endpoint API endpoint
     * @param string $method HTTP method (GET, POST, etc.)
     * @param array $data Data to send with the request (for POST, PUT, PATCH)
     * @return array Response data or error details
     */
    private function apiRequest($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->baseUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken,
            'Content-Type: application/json'
        ]);

        if ($method !== 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decodedResponse = json_decode($response, true);
        if ($httpCode >= 400) {
            return ['error' => $decodedResponse['errorCode'] ?? 'Unknown error', 'message' => $decodedResponse['message'] ?? 'No message provided', 'code' => $httpCode];
        }
        return $decodedResponse;
    }

    /**
     * Create an agreement for signing
     * @param array $agreementData Data for creating an agreement
     * @return array Response from Adobe Sign API
     */
    public function createAgreement($agreementData) {
        return $this->apiRequest('/agreements', 'POST', $agreementData);
    }

    /**
     * Get details of an agreement
     * @param string $agreementId ID of the agreement
     * @return array Agreement details
     */
    public function getAgreement($agreementId) {
        return $this->apiRequest('/agreements/' . $agreementId);
    }

    /**
     * Send an agreement for signing
     * @param string $agreementId ID of the agreement
     * @return array Response from Adobe Sign API
     */
    public function sendAgreement($agreementId) {
        return $this->apiRequest('/agreements/' . $agreementId . '/statusUpdate', 'PUT', ['status' => 'IN_PROCESS']);
    }

    /**
     * Download signed documents of an agreement
     * @param string $agreementId ID of the agreement
     * @return string Content of the signed documents or error message
     */
    public function downloadSignedDocuments($agreementId) {
        $ch = curl_init($this->baseUrl . '/agreements/' . $agreementId . '/combinedDocument');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->accessToken
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 400) {
            return json_decode($response, true)['message'] ?? 'Failed to download documents';
        }
        return $response;
    }
}

// Example usage:
// $adobeSign = new AdobeSign('your_access_token_here');
// $agreement = $adobeSign->createAgreement([
//     'fileInfos' => [['documentURL' => 'url_to_your_document']],
//     'name' => 'Test Agreement',
//     'participantSetsInfo' => [['memberInfos' => [['email' => '[email protected]']]]]
// ]);
// $details = $adobeSign->getAgreement($agreement['id']);
// $sent = $adobeSign->sendAgreement($agreement['id']);
// $document = $adobeSign->downloadSignedDocuments($agreement['id']);

?>