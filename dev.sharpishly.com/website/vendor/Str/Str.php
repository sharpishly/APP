<?php
namespace Str;

/**
 * String manipulation class for a custom PHP framework.
 *
 * This class provides static methods for common string operations,
 * designed to be used without external libraries.
 */
class Str
{
    /**
     * Generate a random string of a specified length.
     *
     * @param int $length The length of the desired string.
     * @param string $characters The character set to use (optional).
     * @return string The random string.
     * @throws \Exception If $length is less than 1.
     */
    public static function random(int $length, string $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'): string
    {
        if ($length < 1) {
            throw new \Exception('Length must be greater than or equal to 1');
        }

        $randomString = '';
        $max = strlen($characters) - 1; // Calculate max only once for efficiency

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $max); // Use random_int() for better security
            $randomString .= $characters[$randomIndex];
        }

        return $randomString;
    }

    /**
     * Convert a string to snake case.
     *
     * Example: "camelCaseString" becomes "camel_case_string".
     *
     * @param string $value The string to convert.
     * @param string $separator The separator to use (default: '_').
     * @return string The snake case string.
     */
    public static function snake(string $value, string $separator = '_'): string
    {
        if (empty($value)) {
            return '';
        }

        // Use preg_replace_callback to handle more complex cases and avoid unnecessary loops
        return strtolower(preg_replace('/(?<!^)([A-Z])/', $separator . '$1', $value));
    }

    /**
     * Convert a string to camel case.
     *
     * Example: "snake_case_string" becomes "snakeCaseString".
     *
     * @param string $value The string to convert.
     * @return string The camel case string.
     */
    public static function camel(string $value): string
    {
        if (empty($value)) {
            return '';
        }

        $parts = explode('_', $value);
        $result = array_shift($parts); // Get the first part without modifying original array

        foreach ($parts as $part) {
            $result .= ucfirst($part); // Capitalize the first letter of each part
        }

        return $result;
    }

    /**
     * Convert a string to title case.
     *
     * Example: "a title case string" becomes "A Title Case String".
     *
     * @param string $value The string to convert.
     * @return string The title case string.
     */
    public static function title(string $value): string
    {
        return ucwords(strtolower($value));
    }

    /**
     * Check if a string starts with a given substring.
     *
     * @param string $haystack The string to search in.
     * @param string|array $needle The substring(s) to check for.
     * @return bool True if the string starts with the substring, false otherwise.
     */
    public static function startsWith(string $haystack, $needle): bool
    {
        if (is_array($needle)) {
            foreach ($needle as $n) {
                if (str_starts_with($haystack, $n)) { // Use native str_starts_with
                    return true;
                }
            }
            return false;
        }

        return str_starts_with($haystack, $needle); // Use native str_starts_with
    }

    /**
     * Check if a string ends with a given substring.
     *
     * @param string $haystack The string to search in.
     * @param string|array $needle The substring(s) to check for.
     * @return bool True if the string ends with the substring, false otherwise.
     */
    public static function endsWith(string $haystack, $needle): bool
    {
         if (is_array($needle)) {
            foreach ($needle as $n) {
                if (str_ends_with($haystack, $n)) { // Use native str_ends_with
                    return true;
                }
            }
            return false;
        }
        return str_ends_with($haystack, $needle); //use native
    }

    /**
     * Determine if a string contains a given substring.
     *
     * @param string $haystack The string to search in.
     * @param string|array $needle The substring(s) to check for.
     * @return bool True if the string contains the substring, false otherwise.
     */
    public static function contains(string $haystack, $needle): bool
    {
        if (is_array($needle)) {
            foreach ($needle as $n) {
                if (str_contains($haystack, $n)) { // Use native str_contains
                    return true;
                }
            }
            return false;
        }
        return str_contains($haystack, $needle); // Use native str_contains
    }

     /**
     * Limits the number of characters in a string.
     *
     * @param string $value The string to limit.
     * @param int $limit The maximum number of characters.
     * @param string $end The string that's appended to the end.
     * @return string The limited string.
     */
    public static function limit(string $value, int $limit = 100, string $end = '...'): string
    {
        if (mb_strlen($value) <= $limit) {
            return $value;
        }

        return rtrim(mb_substr($value, 0, $limit)) . $end;
    }

     /**
     * Returns the portion of string specified by the start and length parameters.
     *
     * @param string $string The input string.
     * @param int $start The starting position.
     * @param int|null $length The maximum length of the substring to return. If NULL, the remainder of the string is returned.
     * @return string
     */
    public static function substr(string $string, int $start, ?int $length = null): string {
        if ($length === null) {
            return mb_substr($string, $start);
        }
        return mb_substr($string, $start, $length);
    }

    /**
     * Replace the first occurrence of a string within another string.
     *
     * @param string $search The string to search for.
     * @param string $replace The string to replace with.
     * @param string $subject The string to search in.
     * @return string The string with the replacement applied.
     */
    public static function replaceFirst(string $search, string $replace, string $subject): string
    {
        $position = strpos($subject, $search);
        if ($position === false) {
            return $subject;
        }
        return substr_replace($subject, $replace, $position, strlen($search));
    }

    /**
     * Replace the last occurrence of a string within another string.
     *
     * @param string $search The string to search for.
     * @param string $replace The string to replace with.
     * @param string $subject The string to search in.
     * @return string The string with the replacement applied.
     */
    public static function replaceLast(string $search, string $replace, string $subject): string
    {
        $position = strrpos($subject, $search);
        if ($position === false) {
            return $subject;
        }
        return substr_replace($subject, $replace, $position, strlen($search));
    }

     /**
     * Remove a substring from the beginning of a string.
     *
     * @param string $string The original string.
     * @param string $remove The string to remove.
     * @return string The string with the prefix removed.
     */
    public static function removeStart(string $string, string $remove): string
    {
        if (empty($remove)) {
            return $string;
        }
        if (str_starts_with($string, $remove)) {
            return substr($string, strlen($remove));
        }
        return $string;
    }

    /**
     * Remove a substring from the end of a string.
     *
     * @param string $string The original string.
     * @param string $remove The string to remove.
     * @return string The string with the suffix removed.
     */
    public static function removeEnd(string $string, string $remove): string
    {
         if (empty($remove)) {
            return $string;
        }
        if (str_ends_with($string, $remove)) {
            return substr($string, 0, strlen($string) - strlen($remove));
        }
        return $string;
    }
}
