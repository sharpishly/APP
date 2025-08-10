<?php

namespace Icloud;

class Icloud {
    /** @var string */
    private string $username;
    /** @var string */
    private string $password;
    /** @var string|null */
    private ?string $token = null;

    /**
     * Constructor to initialize iCloud session.
     *
     * @param string $username
     * @param string $password
     */
    public function __construct(string $username, string $password) {
        $this->username = $username;
        $this->password = $password;
        $this->initSession();
    }

    /**
     * Initializes the iCloud session and retrieves an authentication token.
     *
     * @return void
     */
    private function initSession(): void {
        $postData = [
            'j_username' => $this->username,
            'j_password' => $this->password
        ];

        $ch = curl_init('https://idmsa.apple.com/idms/authenticate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response && strpos($response, 'appleid_session_id') !== false) {
            $this->token = explode('appleid_session_id', $response)[1];
            echo "Authentication successful. Token: $this->token\n";
        } else {
            throw new RuntimeException("Authentication failed.");
        }
    }

    /**
     * Retrieves contacts from iCloud.
     *
     * @return array|null
     */
    public function getContacts(): ?array {
        return $this->fetchData('https://p22-contacts.icloud.com/contacts');
    }

    /**
     * Retrieves calendar data from iCloud.
     *
     * @return array|null
     */
    public function getCalendar(): ?array {
        return $this->fetchData('https://p22-calendars.icloud.com/calendars');
    }

    /**
     * Uploads a file to iCloud.
     *
     * @param string $filePath
     * @return array|null
     */
    public function uploadFile(string $filePath): ?array {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException("File does not exist: $filePath");
        }
        
        $ch = curl_init('https://p22-files.icloud.com/files');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ['file' => new CURLFile($filePath)]);
        
        return $this->executeCurl($ch);
    }

    /**
     * Downloads a file from iCloud.
     *
     * @param string $fileId
     * @return string|null
     */
    public function downloadFile(string $fileId): ?string {
        return $this->fetchRawData('https://p22-files.icloud.com/files/' . $fileId);
    }

    /**
     * Deletes a file from iCloud.
     *
     * @param string $fileId
     * @return array|null
     */
    public function deleteFile(string $fileId): ?array {
        $ch = curl_init('https://p22-files.icloud.com/files/' . $fileId);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        
        return $this->executeCurl($ch);
    }

    /**
     * Fetches JSON data from a given URL.
     *
     * @param string $url
     * @return array|null
     */
    private function fetchData(string $url): ?array {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token,
            'Accept: application/json'
        ]);
        
        return $this->executeCurl($ch);
    }

    /**
     * Fetches raw response data from a given URL.
     *
     * @param string $url
     * @return string|null
     */
    private function fetchRawData(string $url): ?string {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->token
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response ?: null;
    }

    /**
     * Executes a cURL request and returns the decoded JSON response.
     *
     * @param resource $ch
     * @return array|null
     */
    private function executeCurl($ch): ?array {
        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response ? json_decode($response, true) : null;
    }
}
?>
