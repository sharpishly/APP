<?php

namespace GitHub;

class GitHub {
    private $apiUrl = 'https://api.github.com';
    private $token;

    /**
     * Constructor for GitHub class
     * @param string $token Personal Access Token for authentication
     */
    public function __construct($token) {
        $this->token = $token;
    }

    /**
     * Make an API request to GitHub
     * @param string $endpoint API endpoint
     * @param string $method HTTP method (GET, POST, etc.)
     * @param array $data Data to send with the request (for POST, PUT, PATCH)
     * @return array Response data or error details
     */
    private function apiRequest($endpoint, $method = 'GET', $data = null) {
        $ch = curl_init($this->apiUrl . $endpoint);
        $headers = [
            'Authorization: Bearer ' . $this->token,
            'User-Agent: PHP-GitHub-API-Client',
            'Accept: application/vnd.github.v3+json',
            'Content-Type: application/json'
        ];
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

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
            return [
                'error' => $decodedResponse['message'] ?? 'Unknown error',
                'code' => $httpCode,
                'response' => $decodedResponse
            ];
        }
        return $decodedResponse;
    }

    /**
     * Get user details
     * @param string $username GitHub username
     * @return array User data
     */
    public function getUser($username) {
        return $this->apiRequest('/users/' . $username);
    }

    /**
     * Get repositories for a user
     * @param string $username GitHub username
     * @return array List of repositories
     */
    public function getUserRepos($username) {
        return $this->apiRequest('/users/' . $username . '/repos');
    }

    /**
     * Create a new repository
     * @param string $name Repository name
     * @param string $description Repository description
     * @param bool $private If the repository should be private
     * @return array Response from GitHub
     */
    public function createRepo($name, $description = '', $private = false) {
        return $this->apiRequest('/user/repos', 'POST', [
            'name' => $name,
            'description' => $description,
            'private' => $private,
            'auto_init' => true  // Automatically initialize repository with README
        ]);
    }

    /**
     * Get file data from the repository
     * @param string $repo Owner/repo name
     * @param string $path Path to the file in the repo
     * @param string $branch The branch to get the file from (default is 'main')
     * @return array Response from GitHub API
     */
    private function getFileData($repo, $path, $branch) {
        return $this->apiRequest("/repos/$repo/contents/$path?ref=$branch");
    }

    /**
     * Create a new file in the repository
     * @param string $repo Owner/repo name
     * @param string $path Path to the file
     * @param string $content Content of the file
     * @param string $branch The branch to commit to
     * @return array Response from GitHub API
     */
    private function createFile($repo, $path, $content, $branch) {
        return $this->apiRequest("/repos/$repo/contents/$path", 'PUT', [
            'message' => 'Create deploy.yml',
            'content' => base64_encode($content),
            'branch' => $branch
        ]);
    }

    /**
     * Update an existing file in the repository
     * @param string $repo Owner/repo name
     * @param string $path Path to the file
     * @param string $content Content of the file
     * @param string $sha The current SHA of the file (used for updating)
     * @param string $branch The branch to commit to
     * @return array Response from GitHub API
     */
    private function updateFile($repo, $path, $content, $sha, $branch) {
        return $this->apiRequest("/repos/$repo/contents/$path", 'PUT', [
            'message' => 'Update deploy.yml',
            'content' => base64_encode($content),
            'sha' => $sha,
            'branch' => $branch
        ]);
    }

    /**
     * Create or update the deploy.yml file in the .github/workflows directory
     * @param string $repo Owner/repo name (e.g., 'username/repo')
     * @param string $yamlContent The content of the deploy.yml file to commit
     * @param string $branch The branch to commit to (default is 'main')
     * @return array Response from the GitHub API
     */
    public function saveDeployYaml($repo, $yamlContent, $branch = 'main') {
        $path = '.github/workflows/deploy.yml';
        $fileData = $this->getFileData($repo, $path, $branch);
        
        if (isset($fileData['sha'])) {
            return $this->updateFile($repo, $path, $yamlContent, $fileData['sha'], $branch);
        } else {
            return $this->createFile($repo, $path, $yamlContent, $branch);
        }
    }
}



