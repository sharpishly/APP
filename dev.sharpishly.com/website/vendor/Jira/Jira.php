<?php

namespace JiraClient;

class JiraClient {

    private $jiraUrl;
    private $username;
    private $passwordOrToken; // Can be password or API token

    public function __construct($jiraUrl, $username, $passwordOrToken) {
        $this->jiraUrl = rtrim($jiraUrl, '/'); // Remove trailing slash if present
        $this->username = $username;
        $this->passwordOrToken = $passwordOrToken;
    }

    private function makeRequest($method, $endpoint, $data = null) {
        $url = $this->jiraUrl . "/rest/api/latest/" . $endpoint;  // Using "latest" API version. Consider a specific version.

        $ch = curl_init($url);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json"
            ],
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_USERPWD => $this->username . ":" . $this->passwordOrToken, // Basic Authentication or Token
        ]);

        if ($data) {
            $jsonData = json_encode($data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
        }

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new \Exception("cURL Error: " . curl_error($ch));
        }

        curl_close($ch);

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode >= 400) { // Check for API errors
          $errorData = json_decode($response, true);
          $errorMessage = isset($errorData['errorMessages']) ? implode(", ", $errorData['errorMessages']) : (isset($errorData['errors']) ? print_r($errorData['errors'], true) : "HTTP Error: " . $httpCode . " - " . $response); // Extract error message
          throw new \Exception($errorMessage);
        }

        return json_decode($response, true);
    }



    // Example Jira API Methods:

    public function getIssue($issueKey) {
        return $this->makeRequest("GET", "issue/" . $issueKey);
    }

    public function createIssue($issueData) { // Example $issueData: see Jira API docs
        return $this->makeRequest("POST", "issue", $issueData);
    }

    public function updateIssue($issueKey, $issueData) {
      return $this->makeRequest("PUT", "issue/" . $issueKey, $issueData);
    }

    public function deleteIssue($issueKey) {
        return $this->makeRequest("DELETE", "issue/" . $issueKey);
    }

    public function searchIssues($jql) {
        return $this->makeRequest("POST", "search", ["jql" => $jql]); // Use POST for search
    }

    // ... Add other Jira API methods as needed ...

}

?>