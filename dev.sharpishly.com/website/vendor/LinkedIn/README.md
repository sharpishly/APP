# LinkedIn API PHP Class

This PHP class (`LinkedIn.php`) provides functionality to interact with the LinkedIn API, allowing users to retrieve the latest job postings and submit job applications. It uses OAuth 2.0 for authentication and handles HTTP requests using cURL without relying on third-party packages.

## Prerequisites

- **PHP 7.4 or higher** with cURL extension enabled.
- A **LinkedIn Developer account** and a registered app in the [LinkedIn Developer Portal](https://developer.linkedin.com).
- **Client ID** and **Client Secret** from your LinkedIn app.
- A **Redirect URI** registered in your LinkedIn app settings.
- Access to LinkedIn API permissions (e.g., `r_liteprofile`, `r_emailaddress`, `w_member_social`). Note that job-related APIs may require Talent Solutions partner access.

## Setup

1. **Clone or Download the Class**:
   Save `LinkedIn.php` in your project directory.

2. **Configure Your LinkedIn App**:
   - Log in to the [LinkedIn Developer Portal](https://developer.linkedin.com).
   - Create a new app or use an existing one.
   - Add the redirect URI (e.g., `http://yourdomain.com/callback.php`) to your app's OAuth 2.0 settings.
   - Note your app's **Client ID** and **Client Secret**.

3. **Set Up a Callback Script**:
   Create a PHP script (e.g., `callback.php`) to handle the OAuth redirect and process the authorization code.

## Installation

No external dependencies are required. Simply include `LinkedIn.php` in your PHP project:

```php
require_once 'LinkedIn.php';
```

## Usage

### Initialize the LinkedIn Class

Create an instance of the `LinkedIn` class with your app credentials and redirect URI:

```php
$linkedin = new LinkedIn(
    'YOUR_CLIENT_ID',
    'YOUR_CLIENT_SECRET',
    'YOUR_REDIRECT_URI'
);
```

### Step 1: Authenticate the User

Generate a login URL and direct the user to LinkedIn for authorization:

```php
$loginUrl = $linkedin->getLoginUrl();
echo "Please visit: <a href='$loginUrl'>Login with LinkedIn</a>";
```

This redirects the user to LinkedIn's authorization page. After approval, LinkedIn redirects back to your specified redirect URI with an authorization code in the URL (e.g., `http://yourdomain.com/callback.php?code=AUTH_CODE`).

### Step 2: Exchange Authorization Code for Access Token

In your callback script (e.g., `callback.php`), exchange the authorization code for an access token:

```php
require_once 'LinkedIn.php';

$linkedin = new LinkedIn('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'YOUR_REDIRECT_URI');

if (isset($_GET['code'])) {
    $accessToken = $linkedin->getAccessToken($_GET['code']);
    if ($accessToken) {
        echo "Access Token: " . $accessToken['access_token'];
    } else {
        echo "Failed to obtain access token.";
    }
}
```

### Step 3: Retrieve Latest Jobs

Fetch the latest job postings (limited by the specified number):

```php
$jobs = $linkedin->getLatestJobs(5);
if (isset($jobs['error'])) {
    echo "Error: " . $jobs['error'];
} else {
    foreach ($jobs as $job) {
        echo "Job Title: " . ($job['title'] ?? 'N/A') . "\n";
        echo "Job ID: " . ($job['id'] ?? 'N/A') . "\n\n";
    }
}
```

**Note**: The job search API may require Talent Solutions partner access. Without it, you may receive a permission error.

### Step 4: Apply for a Job

Submit a job application on behalf of the authenticated user:

```php
$jobId = 'urn:li:job:123456'; // Replace with actual job ID
$applicationData = [
    'resume' => 'Base64 encoded resume content',
    'coverLetter' => 'Cover letter text'
];
$result = $linkedin->applyForJob($jobId, $applicationData);
if (isset($result['error'])) {
    echo "Error: " . $result['error'];
} else {
    echo "Application submitted successfully!";
}
```

**Note**: Job application APIs typically require specific permissions (e.g., Talent Solutions). Check your app's access level.

### Step 5: User Registration (Limitations)

The LinkedIn API does not support direct user registration. Users must sign up via LinkedIn's website, and the API handles authentication via OAuth. Attempting to register a user:

```php
$userData = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john.doe@example.com',
    'password' => 'securepassword'
];
$result = $linkedin->registerUser($userData);
echo $result['message'];
```

This will inform you that direct registration is not supported and guide you to use the OAuth flow.

## Example Full Workflow

Create a file (e.g., `index.php`) to initiate the OAuth flow and a callback script (e.g., `callback.php`) to handle the response.

**index.php**:
```php
require_once 'LinkedIn.php';

$linkedin = new LinkedIn('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'http://yourdomain.com/callback.php');
$loginUrl = $linkedin->getLoginUrl();
echo "<a href='$loginUrl'>Login with LinkedIn</a>";
```

**callback.php**:
```php
require_once 'LinkedIn.php';

$linkedin = new LinkedIn('YOUR_CLIENT_ID', 'YOUR_CLIENT_SECRET', 'http://yourdomain.com/callback.php');

if (isset($_GET['code'])) {
    $accessToken = $linkedin->getAccessToken($_GET['code']);
    if ($accessToken) {
        // Fetch jobs
        $jobs = $linkedin->getLatestJobs(5);
        print_r($jobs);

        // Apply for a job
        $jobId = 'urn:li:job:123456';
        $applicationData = [
            'resume' => 'Base64 encoded resume',
            'coverLetter' => 'Applying for the position'
        ];
        $result = $linkedin->applyForJob($jobId, $applicationData);
        print_r($result);
    } else {
        echo "Authentication failed.";
    }
}
```

## Important Notes

- **API Permissions**: Job-related APIs (e.g., job search, applications) often require LinkedIn Talent Solutions partner access. Without it, you may encounter `403 Forbidden` errors. Apply for access via the [LinkedIn Developer Portal](https://developer.linkedin.com).
- **User Registration**: LinkedIn does not allow direct user creation via the API. Users must register on LinkedIn's website, and the API handles authentication.
- **Security**:
  - Store the access token securely (e.g., in a database or session).
  - Avoid exposing `Client ID` and `Client Secret` in public code.
  - Use HTTPS for all requests to prevent token leakage.
- **Rate Limits**: LinkedIn imposes API rate limits. Monitor your app's usage in the Developer Portal.
- **Error Handling**: The class includes basic error handling. Enhance it based on your application's needs (e.g., logging, user-friendly messages).

## Troubleshooting

- **Authentication Errors**: Ensure your `Client ID`, `Client Secret`, and `Redirect URI` match those in the LinkedIn Developer Portal.
- **Permission Denied**: If job-related endpoints return errors, verify your app has the necessary permissions or apply for Talent Solutions access.
- **cURL Issues**: Ensure the cURL extension is enabled in your PHP environment (`php_curl`).

## Limitations

- **Job APIs**: Public access to job postings and applications is restricted. You may need partner status for full functionality.
- **User Registration**: Direct user creation is not supported. Rely on LinkedIn's OAuth flow for authentication.
- **API Changes**: LinkedIn's API may change. Check the [LinkedIn API documentation](https://docs.microsoft.com/en-us/linkedin/) for updates.

## License

This project is licensed under the MIT License.