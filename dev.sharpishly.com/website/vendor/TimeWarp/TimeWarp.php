<?php

namespace TimeWarp;


class TimeWarp {

    public function now() {
        return date('Y-m-d h:i:s'); // Changed 'm' to 'i' for minutes.
    }

    /**
     * Converts a date string from 'Y-m-d h:i:s' format to two other formats.
     *
     * @param string $dateString The date string to convert.
     * @return array An array containing the original date string and the two converted date strings.
     * Returns an array of empty strings on error.
     */
    public function convertDateFormat(string $dateString): array {
        try {
            // Create a DateTime object from the input string.
            $dateTime = new \DateTime($dateString);

            // Format the date into the first desired format: 'd/m/Y h:ia'
            $format1 = $dateTime->format('d/m/Y g:ia');

            // Return the original and the two formatted dates.
            return [
                'original' => $dateString,
                'format1'  => $format1,
            ];
        } catch (\Exception $e) {
            // Handle any exceptions, such as invalid date strings.
            return [
                'original' => '',
                'format1'  => '',
            ];
        }
    }
}

?>