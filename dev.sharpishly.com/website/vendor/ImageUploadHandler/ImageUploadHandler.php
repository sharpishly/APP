<?php

namespace NatsExample;

use Nats\Nats;
use PDO;
use Exception;

/**
 * Handles image uploads and publishes messages to NATS.
 */
class ImageUploadHandler {
    private Nats $nats;
    private const SUBJECT_PROCESS = 'image.process';

    public function __construct(Nats $nats) {
        $this->nats = $nats;
    }

    /**
     * Handles an image upload and publishes an event.
     */
    public function handleUpload(string $imagePath, int $userId): void {
        try {
            if (!file_exists($imagePath)) {
                throw new Exception("Image file does not exist: $imagePath");
            }

            $message = json_encode(['imagePath' => $imagePath, 'userId' => $userId]);
            $this->nats->publish(self::SUBJECT_PROCESS, $message);

            error_log("Image upload processed, message published: $message");
        } catch (Exception $e) {
            error_log("Error in handleUpload: " . $e->getMessage());
        }
    }
}

/**
 * Processes images and updates the database.
 */
class ImageProcessor {
    private Nats $nats;
    private PDO $db;
    private const SUBJECT_PROCESSED = 'image.processed';

    public function __construct(Nats $nats, PDO $db) {
        $this->nats = $nats;
        $this->db = $db;
    }

    /**
     * Processes an image and updates the database.
     */
    public function processImage(string $imagePath): void {
        try {
            if (!file_exists($imagePath)) {
                throw new Exception("Processed image does not exist: $imagePath");
            }

            // Simulate DB update
            $stmt = $this->db->prepare("UPDATE images SET processed = 1 WHERE path = :path");
            $stmt->execute(['path' => $imagePath]);

            $this->nats->publish(self::SUBJECT_PROCESSED, json_encode(['imagePath' => $imagePath]));

            error_log("Image processed and updated in database: $imagePath");
        } catch (Exception $e) {
            error_log("Error in processImage: " . $e->getMessage());
        }
    }
}

/**
 * Listens for image processing tasks and executes processing.
 */
class ImageProcessorWorker {
    private Nats $nats;
    private ImageProcessor $processor;

    public function __construct(Nats $nats, ImageProcessor $processor) {
        $this->nats = $nats;
        $this->processor = $processor;
    }

    /**
     * Handles an image processing task.
     */
    public function handleTask(string $imagePath): void {
        try {
            $this->processor->processImage($imagePath);
            error_log("Image processing task completed: $imagePath");
        } catch (Exception $e) {
            error_log("Error in handleTask: " . $e->getMessage());
        }
    }
}

?>
