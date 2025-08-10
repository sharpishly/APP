<?php

namespace App\Core;

class ExpiredActivity {

    private $timeoutMinutes;
    private $loginPage;
    private $sessionActivityKey = 'LAST_ACTIVITY'; // Key to store last activity timestamp in session
    private $sessionUserKey = 'user'; // Key to check if user is logged in (from $_SESSION['user'])

    /**
     * Constructor for ExpiredActivity.
     *
     * @param int $timeoutMinutes The inactivity timeout duration in minutes.
     * @param string $loginPage The URL to redirect to if the session expires.
     * @param string $sessionUserKey The session key that indicates a logged-in user (e.g., 'user' or 'user_id').
     */
    public function __construct($timeoutMinutes = 2, $loginPage = '/login.php', $sessionUserKey = 'user') {
        // Ensure session is started before using $_SESSION
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        $this->timeoutMinutes = $timeoutMinutes;
        $this->loginPage = $loginPage;
        $this->sessionUserKey = $sessionUserKey;
    }

    /**
     * Sets the inactivity timeout duration.
     *
     * @param int $minutes The new timeout duration in minutes.
     */
    public function setInactivityTimeout($minutes) {
        $this->timeoutMinutes = $minutes;
    }

    /**
     * Sets the login page URL for redirection.
     *
     * @param string $url The new login page URL.
     */
    public function setLoginPage($url) {
        $this->loginPage = $url;
    }

    /**
     * Sets the session key used to identify a logged-in user.
     *
     * @param string $key The session key (e.g., 'user' or 'user_id').
     */
    public function setSessionUserKey($key) {
        $this->sessionUserKey = $key;
    }

    /**
     * Checks user activity and redirects if the session has expired due to inactivity.
     * Updates the last activity timestamp if the session is active.
     */
    public function checkActivity() {
        // Only proceed if a user is considered logged in
        // Adjust this condition based on how your framework determines a logged-in user
        if (!isset($_SESSION[$this->sessionUserKey]) || empty($_SESSION[$this->sessionUserKey])) {
            // User is not logged in, no need to check inactivity timeout
            return;
        }

        $currentTime = time();
        $lastActivity = $_SESSION[$this->sessionActivityKey] ?? $currentTime; // Get last activity or set to current if not found

        // Calculate timeout in seconds
        $timeoutSeconds = $this->timeoutMinutes * 60;

        // Check if inactivity period has passed
        if (($currentTime - $lastActivity) > $timeoutSeconds) {
            $this->destroySession();
            // Redirect to login page with a parameter indicating session expiration
            $this->redirect($this->loginPage . '?session_expired=true');
        }

        // If active, update last activity time on every page load
        $_SESSION[$this->sessionActivityKey] = $currentTime;
    }

    /**
     * Destroys the current session completely.
     */
    public function destroySession() {
        $_SESSION = array(); // Unset all session variables

        // If using cookies, destroy the session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_destroy(); // Destroy the session data on the server
    }

    /**
     * Performs a HTTP redirect to the specified URL and exits the script.
     *
     * @param string $url The URL to redirect to.
     */
    private function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    /**
     * Returns the inactivity timeout in milliseconds for JavaScript.
     *
     * @return int Timeout in milliseconds.
     */
    public function getTimeoutMilliseconds() {
        return $this->timeoutMinutes * 60 * 1000;
    }

    /**
     * Returns the session heartbeat interval in milliseconds for JavaScript.
     * This should be less than the actual timeout to keep the session alive.
     *
     * @return int Heartbeat interval in milliseconds.
     */
    public function getHeartbeatIntervalMilliseconds() {
        // Set heartbeat to be roughly half the timeout, but at least 30 seconds
        return max(30 * 1000, ($this->timeoutMinutes * 60 * 1000) / 2);
    }
}

?>