<?php

namespace CurlRequest;

use Exception;

/**
 * A standalone class for making HTTP requests using cURL.
 * This class centralizes all HTTP requests and does not rely on any external libraries.
 */
class CurlRequest
{
    /**
     * Sends an HTTP request.
     * @param string $url The URL to send the request to.
     * @param string $method The HTTP method (e.g., 'GET', 'POST', 'PUT', 'DELETE').
     * @param array $options An array of options to configure the request:
     * - 'headers' (array, optional):  An array of HTTP headers (e.g., ['Content-Type: application/json']).
     * - 'body' (string|array, optional): The request body for POST, PUT, etc.  Will be JSON-encoded if an array.
     * - 'timeout' (int, optional):  The request timeout in seconds (default: 30).
     * - 'connect_timeout' (int, optional): The connection timeout in seconds (default: 10).
     * - 'verify_ssl' (bool, optional): Whether to verify SSL certificates (default: true).
     * @return array The response data, including 'body', 'headers', and 'status'.
     * @throws Exception on error
     */
    public static function send(string $url, string $method = 'GET', array $options = []): array
    {
        $curl = curl_init($url);
        if ($curl === false) {
            throw new Exception("cURL initialization failed for URL: $url");
        }

        // Set default options
        $defaultOptions = [
            'headers' => [],
            'body' => null,
            'timeout' => 30,
            'connect_timeout' => 10,
            'verify_ssl' => true,
        ];
        $options = array_merge($defaultOptions, $options);

        // Set cURL options
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        curl_setopt($curl, CURLOPT_TIMEOUT, $options['timeout']);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $options['connect_timeout']);

        if ($options['verify_ssl']) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // Verify the hostname
        } else {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // **DO NOT USE IN PRODUCTION UNLESS ABSOLUTELY NECESSARY**
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); // **DO NOT USE IN PRODUCTION UNLESS ABSOLUTELY NECESSARY**
        }


        // Handle request body
        if ($method === 'POST' || $method === 'PUT' || $method === 'PATCH') {
            if (is_array($options['body'])) {
                $options['body'] = json_encode($options['body']);
                if (!in_array('Content-Type: application/json', $options['headers'])) {
                    $options['headers'][] = 'Content-Type: application/json';
                }
            }
            curl_setopt($curl, CURLOPT_POSTFIELDS, $options['body']);
        }

        // Execute the request
        $response = curl_exec($curl);
        if ($response === false) {
            $error = curl_error($curl);
            $errno = curl_errno($curl);
            curl_close($curl);
            throw new Exception("cURL error ($errno) for URL $url: $error");
        }

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $responseHeaders = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
        $headerString = substr($response, 0, $responseHeaders);
        $body = substr($response, $responseHeaders);
        $headers = self::parseHeaders($headerString); // Use the helper

        curl_close($curl);

        // Check for HTTP errors (outside the cURL error check)
        if ($statusCode < 200 || $statusCode >= 300) {
            self::handleHttpError($statusCode, $body, $url);
        }

        return [
            'body' => $body,
            'headers' => $headers,
            'status' => $statusCode,
        ];
    }

    /**
     * Parses the response headers from a string into an array.
     * @param string $headerString The raw header string.
     * @return array An array of headers.
     */
    private static function parseHeaders(string $headerString): array
    {
        $headers = [];
        $lines = explode("\r\n", trim($headerString));
        foreach ($lines as $line) {
            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = trim(strtolower($key));
                $value = trim($value);
                if (isset($headers[$key])) {
                    if (!is_array($headers[$key])) {
                        $headers[$key] = [$headers[$key]];
                    }
                    $headers[$key][] = $value;
                } else {
                    $headers[$key] = $value;
                }
            } elseif (preg_match('#HTTP/\d\.\d (\d{3})#', $line, $matches)) {
                $headers['status_code'] = (int) $matches[1];
            }
        }
        return $headers;
    }

    /**
     * Handles HTTP errors based on the status code.
     * @param int $statusCode The HTTP status code.
     * @param string $responseBody The response body.
     * @param string $url The URL that was requested.
     * @throws Exception
     */
    private static function handleHttpError(int $statusCode, string $responseBody, string $url): void
    {
        $errorMessage = "HTTP error $statusCode from $url: ";
        $errorData = json_decode($responseBody, true); //attempt to decode
        if ($errorData && isset($errorData['message'])) {
            $errorMessage .= $errorData['message'];
        } else {
            $errorMessage .= $responseBody; // Append the raw response if not JSON
        }
        switch ($statusCode) {
            case 400:
                throw new Exception($errorMessage, 400); // Bad Request
            case 401:
                throw new Exception($errorMessage, 401); // Unauthorized
            case 402:
                throw new Exception($errorMessage, 402); // Payment Required
            case 403:
                throw new Exception($errorMessage, 403); // Forbidden
            case 404:
                throw new Exception($errorMessage, 404); // Not Found
            case 500:
                throw new Exception($errorMessage, 500); // Internal Server Error
            default:
                throw new Exception($errorMessage, $statusCode); // Generic error
        }
    }
}
