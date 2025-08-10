<?php

namespace SyncFile;

/**
 * Class SyncFile
 * Handles file uploads, validation, and moving files to a permanent location.
 */
class SyncFile {

    private $uploadDirectory;
    private $maxAllowedFileSize;
    private $allowedMimeTypes;
    private $errors = [];
    private $uploadedFileInfo = null;

    /**
     * Constructor for the SyncFile class.
     *
     * @param string $uploadDir The directory where files will be permanently stored.
     * This directory must exist and be writable by the web server.
     * @param int $maxFileSize The maximum allowed file size in bytes (e.g., 5 * 1024 * 1024 for 5MB).
     * @param array $allowedTypes An array of allowed MIME types (e.g., ['application/pdf', 'image/jpeg']).
     */
    public function __construct(string $uploadDir, int $maxFileSize, array $allowedTypes) {
        $this->uploadDirectory = rtrim($uploadDir, '/') . '/'; // Ensure trailing slash
        $this->maxAllowedFileSize = $maxFileSize;
        $this->allowedMimeTypes = $allowedTypes;

        // Ensure the upload directory exists and is writable
        if (!is_dir($this->uploadDirectory)) {
            // Attempt to create the directory if it doesn't exist
            if (!mkdir($this->uploadDirectory, 0755, true)) {
                $this->addError("Upload directory '{$this->uploadDirectory}' does not exist and could not be created.");
                error_log("SyncFile DEBUG: Constructor: Directory '{$this->uploadDirectory}' does not exist and mkdir failed."); // DEBUG
            }
        }
        if (!is_writable($this->uploadDirectory)) {
            $this->addError("Upload directory '{$this->uploadDirectory}' is not writable. Please check permissions.");
            error_log("SyncFile DEBUG: Constructor: Directory '{$this->uploadDirectory}' is not writable."); // DEBUG
        } else {
            error_log("SyncFile DEBUG: Constructor: Directory '{$this->uploadDirectory}' is writable."); // DEBUG
        }
    }

    /**
     * Adds an error message to the internal errors array.
     *
     * @param string $message The error message.
     */
    private function addError(string $message) {
        $this->errors[] = $message;
    }

    /**
     * Returns an array of all collected error messages.
     *
     * @return array An array of error strings.
     */
    public function getErrors(): array {
        return $this->errors;
    }

    /**
     * Returns information about the successfully uploaded file.
     *
     * @return array|null An array containing 'name', 'type', 'size', 'path' of the uploaded file, or null if no file was successfully uploaded.
     */
    public function getUploadedFileInfo(): ?array {
        return $this->uploadedFileInfo;
    }

    /**
     * Handles the file upload process.
     *
     * @param string $inputName The 'name' attribute of the file input field (e.g., 'myFile').
     * @return bool True on successful upload and move, false otherwise.
     */
    public function handleUpload(string $inputName): bool {
        $this->errors = []; // Reset errors for a new upload attempt
        $this->uploadedFileInfo = null; // Reset file info

        // Check if the file input exists in $_FILES and no PHP-level upload errors occurred
        if (!isset($_FILES[$inputName])) {
            $this->addError("File input field '{$inputName}' not found in \$_FILES.");
            error_log("SyncFile DEBUG: handleUpload: File input field '{$inputName}' not found in \$_FILES.");
            return false;
        }

        $file = $_FILES[$inputName];

        if ($file['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = $this->getPhpUploadErrorMessage($file['error']);
            $this->addError($errorMessage);
            error_log("SyncFile DEBUG: handleUpload: PHP upload error: " . $errorMessage . " (Code: " . $file['error'] . ")");
            return false;
        }

        // Basic validation: Check if file is actually an uploaded file
        if (!is_uploaded_file($file['tmp_name'])) {
            $this->addError("Potential file upload attack: '{$file['name']}' was not a valid uploaded file.");
            error_log("SyncFile DEBUG: handleUpload: '{$file['name']}' was not a valid uploaded file (is_uploaded_file failed).");
            return false;
        }

        // Validate file size
        if ($file['size'] > $this->maxAllowedFileSize) {
            $this->addError("File size (" . round($file['size'] / 1024 / 1024, 2) . " MB) exceeds the maximum allowed (" . round($this->maxAllowedFileSize / 1024 / 1024, 2) . " MB).");
            error_log("SyncFile DEBUG: handleUpload: File size exceeded. Size: {$file['size']}, Max: {$this->maxAllowedFileSize}");
            return false;
        }

        // Validate file type (MIME type)
        if (!in_array($file['type'], $this->allowedMimeTypes)) {
            $this->addError("Invalid file type: '{$file['type']}'. Allowed types are: " . implode(', ', $this->allowedMimeTypes) . ".");
            error_log("SyncFile DEBUG: handleUpload: Invalid file type. Type: '{$file['type']}', Allowed: " . implode(', ', $this->allowedMimeTypes));
            return false;
        }

        // Generate a unique and safe filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFileName = uniqid() . '.' . $fileExtension; // Unique ID to prevent collisions
        $destinationPath = $this->uploadDirectory . $newFileName;

        // --- Crucial Debugging Point ---
        error_log("SyncFile DEBUG: handleUpload: Attempting to move file from '{$file['tmp_name']}' to '{$destinationPath}'");
        error_log("SyncFile DEBUG: handleUpload: Destination directory exists: " . (is_dir($this->uploadDirectory) ? 'Yes' : 'No'));
        error_log("SyncFile DEBUG: handleUpload: Destination directory writable: " . (is_writable($this->uploadDirectory) ? 'Yes' : 'No'));


        if (move_uploaded_file($file['tmp_name'], $destinationPath)) {
            $this->uploadedFileInfo = [
                'original_name' => $file['name'],
                'new_name' => $newFileName,
                'type' => $file['type'],
                'size' => $file['size'],
                'path' => $destinationPath,
            ];
            error_log("SyncFile DEBUG: handleUpload: File '{$newFileName}' moved successfully to '{$destinationPath}'.");
            return true;
        } else {
            $moveErrorMsg = "Failed to move uploaded file '{$file['name']}' from '{$file['tmp_name']}' to '{$destinationPath}'.";
            $this->addError($moveErrorMsg);

            $lastPhpError = error_get_last();
            if ($lastPhpError) {
                $this->addError("PHP Function Error: " . $lastPhpError['message']);
                error_log("SyncFile DEBUG: handleUpload: move_uploaded_file FAILED. PHP Error: " . $lastPhpError['message'] . " (File: " . $lastPhpError['file'] . ", Line: " . $lastPhpError['line'] . ")");
                error_log("SyncFile DEBUG: handleUpload: Last error details: " . print_r($lastPhpError, true));
            } else {
                error_log("SyncFile DEBUG: handleUpload: move_uploaded_file FAILED, but no specific PHP error found with error_get_last().");
            }
            return false;
        }
    }

    /**
     * Translates PHP upload error codes into human-readable messages.
     *
     * @param int $errorCode The error code from $_FILES['name']['error'].
     * @return string The error message.
     */
    private function getPhpUploadErrorMessage(int $errorCode): string {
        switch ($errorCode) {
            case UPLOAD_ERR_INI_SIZE:
                return "The uploaded file exceeds the upload_max_filesize directive in php.ini.";
            case UPLOAD_ERR_FORM_SIZE:
                return "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.";
            case UPLOAD_ERR_PARTIAL:
                return "The uploaded file was only partially uploaded.";
            case UPLOAD_ERR_NO_FILE:
                return "No file was uploaded.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Missing a temporary folder.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Failed to write file to disk.";
            case UPLOAD_ERR_EXTENSION:
                return "A PHP extension stopped the file upload.";
            default:
                return "Unknown upload error: " . $errorCode;
        }
    }
}

?>