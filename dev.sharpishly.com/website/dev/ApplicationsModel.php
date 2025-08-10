<?php

// applications/Models/ApplicationsModel.php

namespace App\Models; // Adjust namespace according to your framework's structure

use PDO;
use Exception;

/**
 * Class ApplicationsModel
 *
 * This model handles all data interactions related to job applications.
 * It manages application records in the database and orchestrates the
 * submission process to various job platforms by delegating to
 * specific job board service classes.
 */
class ApplicationsModel
{
    /**
     * @var PDO The database connection instance.
     */
    private PDO $db;

    /**
     * @var string The name of the database table for job applications.
     */
    private string $table = 'job_applications';

    /**
     * Constructor
     * Initializes the database connection.
     *
     * @param PDO $db A PDO database connection instance.
     * @throws Exception If the database connection is not provided.
     */
    public function __construct(PDO $db)
    {
        if (!$db) {
            throw new Exception("Database connection not provided to ApplicationsModel.");
        }
        $this->db = $db;
        // Set PDO error mode to exception for better error handling
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Creates a new job application record in the database.
     *
     * @param int $userId The ID of the user submitting the application.
     * @param string $jobTitle The title of the job.
     * @param string $companyName The name of the company.
     * @param string $platform The platform the job was found on (e.g., 'LinkedIn', 'Indeed', 'CompanyWebsite').
     * @param array $applicationData An associative array containing detailed application data
     * (e.g., resume_path, cover_letter_text, custom_fields).
     * @param string $status The initial status of the application (default: 'draft').
     * @param string|null $externalId An optional ID from the external platform if available after submission.
     * @return int|false The ID of the newly created application record, or false on failure.
     */
    public function createApplication(
        int $userId,
        string $jobTitle,
        string $companyName,
        string $platform,
        array $applicationData,
        string $status = 'draft',
        ?string $externalId = null
    ): int|false {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO {$this->table}
                (user_id, job_title, company_name, platform, application_data, status, external_id, created_at, updated_at)
                VALUES (:user_id, :job_title, :company_name, :platform, :application_data, :status, :external_id, NOW(), NOW())
            ");

            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->bindValue(':job_title', $jobTitle, PDO::PARAM_STR);
            $stmt->bindValue(':company_name', $companyName, PDO::PARAM_STR);
            $stmt->bindValue(':platform', $platform, PDO::PARAM_STR);
            $stmt->bindValue(':application_data', json_encode($applicationData), PDO::PARAM_STR); // Store as JSON
            $stmt->bindValue(':status', $status, PDO::PARAM_STR);
            $stmt->bindValue(':external_id', $externalId, PDO::PARAM_STR);

            if ($stmt->execute()) {
                return (int)$this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            // Log the error for debugging purposes
            error_log("Error creating application: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves a job application by its ID.
     *
     * @param int $applicationId The ID of the application to retrieve.
     * @return array|false An associative array of the application data, or false if not found.
     */
    public function getApplicationById(int $applicationId): array|false
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $applicationId, PDO::PARAM_INT);
            $stmt->execute();
            $application = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($application) {
                // Decode JSON data back to array
                $application['application_data'] = json_decode($application['application_data'], true);
            }
            return $application;
        } catch (PDOException $e) {
            error_log("Error fetching application by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieves all job applications for a specific user.
     *
     * @param int $userId The ID of the user.
     * @return array An array of associative arrays, each representing an application.
     */
    public function getAllApplicationsForUser(int $userId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC");
            $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();
            $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($applications as &$app) {
                $app['application_data'] = json_decode($app['application_data'], true);
            }
            return $applications;
        } catch (PDOException $e) {
            error_log("Error fetching all applications for user: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Updates the status of a job application.
     *
     * @param int $applicationId The ID of the application to update.
     * @param string $newStatus The new status (e.g., 'submitted', 'failed', 'accepted').
     * @param string|null $externalId Optional: An external ID from the job platform.
     * @return bool True on success, false on failure.
     */
    public function updateApplicationStatus(int $applicationId, string $newStatus, ?string $externalId = null): bool
    {
        try {
            $sql = "UPDATE {$this->table} SET status = :status, updated_at = NOW()";
            if ($externalId !== null) {
                $sql .= ", external_id = :external_id";
            }
            $sql .= " WHERE id = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':status', $newStatus, PDO::PARAM_STR);
            $stmt->bindValue(':id', $applicationId, PDO::PARAM_INT);
            if ($externalId !== null) {
                $stmt->bindValue(':external_id', $externalId, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating application status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletes a job application record from the database.
     *
     * @param int $applicationId The ID of the application to delete.
     * @return bool True on success, false on failure.
     */
    public function deleteApplication(int $applicationId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $applicationId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error deleting application: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Orchestrates the process of applying to a job on an external platform.
     * This method would typically interact with various "Job Board Service" classes
     * that encapsulate the API logic for specific platforms.
     *
     * @param int $applicationId The ID of the internal application record.
     * @param array $jobDetails An array containing details about the job and the target platform.
     * Expected keys: 'platform', 'job_url', 'api_config', etc.
     * @param array $applicantData An array containing all necessary applicant information
     * (e.g., resume_file_path, cover_letter_content, contact_info).
     * @return array An array indicating success/failure and any messages/external IDs.
     */
    public function applyToJob(int $applicationId, array $jobDetails, array $applicantData): array
    {
        $platform = $jobDetails['platform'] ?? 'unknown';
        $response = ['success' => false, 'message' => 'An unknown error occurred.', 'external_id' => null];

        // Update application status to 'pending' before attempting submission
        $this->updateApplicationStatus($applicationId, 'pending');

        try {
            switch ($platform) {
                case 'linkedin':
                    // Assume you have a LinkedInJobService class
                    // use App\Services\LinkedInJobService; // Include at top of file
                    $linkedInService = new \App\Services\LinkedInJobService(); // Or inject via constructor
                    $result = $linkedInService->submitApplication($jobDetails, $applicantData);
                    break;
                case 'indeed':
                    // Assume you have an IndeedJobService class
                    // use App\Services\IndeedJobService; // Include at top of file
                    $indeedService = new \App\Services\IndeedJobService(); // Or inject via constructor
                    $result = $indeedService->submitApplication($jobDetails, $applicantData);
                    break;
                case 'company_website_api':
                    // For direct company API integrations
                    // use App\Services\CompanyWebsiteJobService;
                    $companyService = new \App\Services\CompanyWebsiteJobService();
                    $result = $companyService->submitApplication($jobDetails, $applicantData);
                    break;
                // Add more cases for other platforms
                default:
                    $response['message'] = "Application to '{$platform}' not supported via API. Please apply manually.";
                    $this->updateApplicationStatus($applicationId, 'manual_required');
                    return $response;
            }

            if ($result['success']) {
                $response['success'] = true;
                $response['message'] = "Application submitted successfully to {$platform}.";
                $response['external_id'] = $result['external_id'] ?? null;
                $this->updateApplicationStatus($applicationId, 'submitted', $response['external_id']);
            } else {
                $response['message'] = "Failed to submit application to {$platform}: " . ($result['message'] ?? 'No specific error message.');
                $this->updateApplicationStatus($applicationId, 'failed');
            }
        } catch (Exception $e) {
            $response['message'] = "Exception during application to {$platform}: " . $e->getMessage();
            $this->updateApplicationStatus($applicationId, 'failed');
            error_log($response['message']);
        }

        return $response;
    }
}

/*
// Example Usage (in a Controller or a test script):

// 1. Establish a PDO database connection (replace with your actual DB config)
try {
    $dsn = 'mysql:host=localhost;dbname=your_database_name;charset=utf8mb4';
    $username = 'your_db_user';
    $password = 'your_db_password';
    $pdo = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    die("DB Connection failed: " . $e->getMessage());
}

// 2. Instantiate the ApplicationsModel
$applicationsModel = new \App\Models\ApplicationsModel($pdo);

// 3. Example: Create a new application record
$userId = 1; // Assuming a logged-in user ID
$jobTitle = "Senior PHP Developer";
$companyName = "Tech Solutions Inc.";
$platform = "linkedin";
$applicationData = [
    'resume_path' => '/uploads/resumes/user1_resume.pdf',
    'cover_letter_text' => 'Dear Hiring Manager...',
    'expected_salary' => '100000',
    'custom_field_1' => 'Value X'
];

$newApplicationId = $applicationsModel->createApplication(
    $userId,
    $jobTitle,
    $companyName,
    $platform,
    $applicationData
);

if ($newApplicationId) {
    echo "Application record created with ID: " . $newApplicationId . "\n";

    // 4. Example: Attempt to apply to the job via an external service
    // (You would need to implement LinkedInJobService, IndeedJobService, etc.)
    $jobDetails = [
        'platform' => 'linkedin',
        'job_url' => 'https://www.linkedin.com/jobs/view/1234567890',
        'api_config' => ['api_key' => 'YOUR_LINKEDIN_API_KEY'], // Or fetch from secure config
        // ... other job-specific details for the API call
    ];
    $applicantData = [
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john.doe@example.com',
        'phone' => '123-456-7890',
        'resume_file_content' => file_get_contents('/path/to/your/resume.pdf'), // Or pass path and service reads it
        'cover_letter_content' => 'This is my cover letter for the role.'
    ];

    // This part requires actual Job Board Service implementations
    // For demonstration, let's mock a service if they don't exist yet
    // In a real scenario, you'd ensure these services are properly instantiated
    // and configured (e.g., via dependency injection).
    if (!class_exists('\App\Services\LinkedInJobService')) {
        // Mock a simple service for testing the model's delegation logic
        namespace App\Services;
        class LinkedInJobService {
            public function submitApplication(array $jobDetails, array $applicantData): array {
                echo "Mock: Submitting application to LinkedIn for " . $jobDetails['job_url'] . "\n";
                // Simulate API call success/failure
                if (rand(0, 1)) { // 50% chance of success
                    return ['success' => true, 'message' => 'Mock LinkedIn success', 'external_id' => 'LI' . uniqid()];
                } else {
                    return ['success' => false, 'message' => 'Mock LinkedIn failure: API error'];
                }
            }
        }
    }

    $applicationResult = $applicationsModel->applyToJob($newApplicationId, $jobDetails, $applicantData);

    if ($applicationResult['success']) {
        echo "Application submission successful: " . $applicationResult['message'] . "\n";
        if ($applicationResult['external_id']) {
            echo "External ID: " . $applicationResult['external_id'] . "\n";
        }
    } else {
        echo "Application submission failed: " . $applicationResult['message'] . "\n";
    }

    // 5. Example: Get application by ID
    $fetchedApplication = $applicationsModel->getApplicationById($newApplicationId);
    if ($fetchedApplication) {
        echo "Fetched Application:\n";
        print_r($fetchedApplication);
    }

    // 6. Example: Get all applications for user
    $userApplications = $applicationsModel->getAllApplicationsForUser($userId);
    echo "All Applications for User {$userId}:\n";
    print_r($userApplications);

} else {
    echo "Failed to create application record.\n";
}

*/