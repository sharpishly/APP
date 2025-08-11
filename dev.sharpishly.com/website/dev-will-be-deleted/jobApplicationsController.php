<?php

// app/Controllers/JobApplicationController.php

namespace App\Controllers; // Adjust namespace according to your framework's structure

use App\Models\ApplicationsModel;
use Exception;

/**
 * Class JobApplicationController
 *
 * This controller manages the user-facing interactions for job applications.
 * It handles displaying application forms, processing submissions, and
 * showing the history of applications.
 */
class JobApplicationController
{
    /**
     * @var ApplicationsModel The model for managing job application data.
     */
    private ApplicationsModel $applicationsModel;

    /**
     * @var mixed A placeholder for your framework's View rendering mechanism.
     */
    private $view; // Could be an instance of a View class, or a callable for rendering

    /**
     * Constructor
     *
     * @param ApplicationsModel $applicationsModel An instance of the ApplicationsModel.
     * @param mixed $view Your framework's view rendering mechanism.
     */
    public function __construct(ApplicationsModel $applicationsModel, $view)
    {
        $this->applicationsModel = $applicationsModel;
        $this->view = $view;
    }

    /**
     * Displays the main job application form.
     * This method is typically accessed via a GET request (e.g., /apply).
     *
     * @return void
     */
    public function index(): void
    {
        // You might fetch some initial data here, e.g., user's saved resume details
        $data = [
            'pageTitle' => 'Apply for Jobs',
            'user' => $this->getCurrentUser(), // Assuming this method exists and returns user data
            'messages' => $_SESSION['messages'] ?? [], // For displaying success/error messages after redirect
            'errors' => $_SESSION['errors'] ?? [],
        ];

        // Clear messages after displaying them
        unset($_SESSION['messages']);
        unset($_SESSION['errors']);

        // Render the application form view
        $this->renderView('job_application/form', $data);
    }

    /**
     * Processes the job application submission.
     * This method is typically accessed via a POST request (e.g., /apply/submit).
     *
     * @return void
     */
    public function submit(): void
    {
        // Basic check for POST request
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setFlashMessage('error', 'Invalid request method.');
            $this->redirectTo('/apply');
            return;
        }

        // 1. Retrieve and Validate Input Data
        $jobTitle = $_POST['job_title'] ?? '';
        $companyName = $_POST['company_name'] ?? '';
        $jobUrl = $_POST['job_url'] ?? '';
        $selectedPlatforms = $_POST['platforms'] ?? []; // Array of platforms to apply to
        $resumeFile = $_FILES['resume'] ?? null;
        $coverLetterContent = $_POST['cover_letter'] ?? '';
        $userId = $this->getCurrentUserId(); // Get current user's ID

        $errors = [];
        if (empty($jobTitle)) {
            $errors[] = 'Job Title is required.';
        }
        if (empty($companyName)) {
            $errors[] = 'Company Name is required.';
        }
        if (empty($jobUrl)) {
            $errors[] = 'Job URL is required.';
        }
        if (empty($selectedPlatforms)) {
            $errors[] = 'At least one platform must be selected.';
        }
        if (empty($resumeFile) || $resumeFile['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Resume file is required and must be uploaded successfully.';
        }
        if (empty($userId)) {
            $errors[] = 'User not authenticated. Please log in.';
        }

        if (!empty($errors)) {
            $this->setFlashMessage('error', $errors);
            $this->redirectTo('/apply');
            return;
        }

        // Handle resume file upload
        $resumePath = null;
        $uploadDir = __DIR__ . '/../../public/uploads/resumes/'; // Adjust path as per your project structure
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }
        $fileName = uniqid('resume_') . '_' . basename($resumeFile['name']);
        $targetFilePath = $uploadDir . $fileName;

        if (move_uploaded_file($resumeFile['tmp_name'], $targetFilePath)) {
            $resumePath = $targetFilePath; // Store the full path or relative path from web root
        } else {
            $this->setFlashMessage('error', 'Failed to upload resume file.');
            $this->redirectTo('/apply');
            return;
        }

        // Prepare common applicant data
        $applicantData = [
            'user_id' => $userId,
            'job_title' => $jobTitle,
            'company_name' => $companyName,
            'job_url' => $jobUrl,
            'resume_file_path' => $resumePath,
            'cover_letter_content' => $coverLetterContent,
            // Add more applicant details (e.g., from user profile or form)
            'first_name' => $this->getCurrentUser()['first_name'] ?? 'Applicant',
            'last_name' => $this->getCurrentUser()['last_name'] ?? 'User',
            'email' => $this->getCurrentUser()['email'] ?? 'applicant@example.com',
            'phone' => $this->getCurrentUser()['phone'] ?? '',
        ];

        $applicationResults = [];
        $overallSuccess = true;

        foreach ($selectedPlatforms as $platform) {
            // 2. Create an internal application record
            $applicationId = $this->applicationsModel->createApplication(
                $userId,
                $jobTitle,
                $companyName,
                $platform,
                $applicantData, // Store full applicant data for record-keeping
                'pending' // Initial status
            );

            if ($applicationId) {
                // 3. Attempt to apply to the job via the specific platform service
                $jobDetailsForPlatform = [
                    'platform' => $platform,
                    'job_url' => $jobUrl,
                    // Add any platform-specific details needed by the service
                ];

                $result = $this->applicationsModel->applyToJob(
                    $applicationId,
                    $jobDetailsForPlatform,
                    $applicantData
                );
                $applicationResults[$platform] = $result;
                if (!$result['success']) {
                    $overallSuccess = false;
                }
            } else {
                $applicationResults[$platform] = [
                    'success' => false,
                    'message' => "Failed to create internal record for {$platform}.",
                    'external_id' => null
                ];
                $overallSuccess = false;
            }
        }

        // 4. Provide Feedback to the User
        if ($overallSuccess) {
            $this->setFlashMessage('success', 'Your applications have been submitted successfully!');
        } else {
            $message = 'Some applications failed to submit. Please check the details:';
            foreach ($applicationResults as $platform => $result) {
                $message .= "\n- {$platform}: " . ($result['success'] ? 'Success' : 'Failed - ' . $result['message']);
            }
            $this->setFlashMessage('warning', $message);
        }

        // Redirect to a results page or history page
        $this->redirectTo('/applications/history');
    }

    /**
     * Displays the history of job applications for the current user.
     *
     * @return void
     */
    public function history(): void
    {
        $userId = $this->getCurrentUserId();
        if (empty($userId)) {
            $this->setFlashMessage('error', 'Please log in to view your application history.');
            $this->redirectTo('/login'); // Redirect to login page
            return;
        }

        $applications = $this->applicationsModel->getAllApplicationsForUser($userId);

        $data = [
            'pageTitle' => 'My Job Application History',
            'applications' => $applications,
            'messages' => $_SESSION['messages'] ?? [],
            'errors' => $_SESSION['errors'] ?? [],
        ];

        unset($_SESSION['messages']);
        unset($_SESSION['errors']);

        $this->renderView('job_application/history', $data);
    }

    /**
     * Helper method to get the current logged-in user's ID.
     * Replace with your actual authentication system's logic.
     *
     * @return int|null The user ID or null if not logged in.
     */
    private function getCurrentUserId(): ?int
    {
        // This is a placeholder. In a real application, you'd use your
        // authentication system (e.g., session, JWT, etc.) to get the user ID.
        // For demonstration, let's assume user ID 1 is logged in.
        session_start(); // Ensure session is started
        return $_SESSION['user_id'] ?? 1; // Default to 1 for testing
    }

    /**
     * Helper method to get current logged-in user's data.
     * Replace with your actual authentication system's logic.
     *
     * @return array An associative array of user data.
     */
    private function getCurrentUser(): array
    {
        // This is a placeholder. Fetch actual user data from your User model.
        return [
            'id' => $this->getCurrentUserId(),
            'first_name' => 'Jane',
            'last_name' => 'Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '555-123-4567',
            // ... other user profile data
        ];
    }

    /**
     * Helper method to set flash messages in the session for redirection.
     *
     * @param string $type 'success', 'error', 'warning'
     * @param string|array $message The message or an array of messages.
     * @return void
     */
    private function setFlashMessage(string $type, string|array $message): void
    {
        session_start(); // Ensure session is started
        if (!isset($_SESSION['messages'])) {
            $_SESSION['messages'] = [];
        }
        if (!isset($_SESSION['errors'])) {
            $_SESSION['errors'] = [];
        }

        if ($type === 'error') {
            $_SESSION['errors'] = array_merge($_SESSION['errors'], (array)$message);
        } else {
            $_SESSION['messages'][$type][] = $message;
        }
    }

    /**
     * Helper method for redirection.
     *
     * @param string $url The URL to redirect to.
     * @return void
     */
    private function redirectTo(string $url): void
    {
        header("Location: {$url}");
        exit();
    }

    /**
     * Helper method to render a view.
     * Replace this with your framework's actual view rendering logic.
     *
     * @param string $viewName The name of the view file (e.g., 'job_application/form').
     * @param array $data Data to pass to the view.
     * @return void
     */
    private function renderView(string $viewName, array $data = []): void
    {
        // This is a simplified example. Your framework's View class
        // would handle paths, templating engines (Twig, Blade, etc.).
        // For demonstration, we'll just include a PHP file.

        // Extract data so variables are available in the view file
        extract($data);

        // Define base path for views. Adjust as per your framework's structure.
        $viewPath = __DIR__ . '/../../app/Views/' . str_replace('.', '/', $viewName) . '.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            // Handle view not found error
            echo "Error: View '{$viewName}' not found at '{$viewPath}'.";
            error_log("View not found: {$viewPath}");
        }
    }
}

/*
// Example of how you might instantiate and use this controller in your `index.php` or router:

// 1. Establish your database connection (e.g., using a DB class or direct PDO)
//    This PDO object would be passed to the ApplicationsModel.
//    Example (simplified):
//    $dsn = 'mysql:host=localhost;dbname=your_database_name;charset=utf8mb4';
//    $username = 'your_db_user';
//    $password = 'your_db_password';
//    try {
//        $pdo = new PDO($dsn, $username, $password);
//        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
//    } catch (PDOException $e) {
//        die("DB Connection failed: " . $e->getMessage());
//    }

// 2. Instantiate the ApplicationsModel
// $applicationsModel = new \App\Models\ApplicationsModel($pdo);

// 3. Define your View rendering mechanism (could be a simple function or a class instance)
//    For this example, we're using a simple closure for demonstration.
//    In a real framework, this would be a dedicated View class.
// $viewRenderer = function($viewName, $data = []) {
//     extract($data);
//     $viewPath = __DIR__ . '/../app/Views/' . str_replace('.', '/', $viewName) . '.php';
//     if (file_exists($viewPath)) {
//         include $viewPath;
//     } else {
//         echo "View not found: " . $viewName;
//     }
// };

// 4. Instantiate the Controller
// $jobApplicationController = new \App\Controllers\JobApplicationController($applicationsModel, $viewRenderer);

// 5. Route requests (simplified example, your router would handle this)
//    if ($_SERVER['REQUEST_URI'] === '/apply' && $_SERVER['REQUEST_METHOD'] === 'GET') {
//        $jobApplicationController->index();
//    } elseif ($_SERVER['REQUEST_URI'] === '/apply/submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
//        $jobApplicationController->submit();
//    } elseif ($_SERVER['REQUEST_URI'] === '/applications/history' && $_SERVER['REQUEST_METHOD'] === 'GET') {
//        $jobApplicationController->history();
//    } else {
//        // Handle 404 or other routes
//        echo "404 Not Found";
//    }
*/