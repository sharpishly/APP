<?php

namespace MigrationToFormArrayConverter;

use Exception;

/**
 * Class MigrationToFormArrayConverter
 * Converts a database migration array to a form array for use in models and populates it with record data.
 */
class MigrationToFormArrayConverter
{
    /**
     * Converts a database migration array to a form array.
     *
     * @param array $migration The database migration array.
     * @return array The form array.
     * @throws Exception If the migration array is invalid.
     */
    public static function convert(array $migration): array
    {
        return self::convertPopulateRecords($migration, []);
    }

    /**
     * Converts a database migration array to a form array and populates it with record data.
     *
     * @param array $migration The database migration array.
     * @param array $record An associative array representing the record to populate the form with.
     * @return array The populated form array.
     * @throws Exception If the migration array is invalid.
     */
    public static function convertPopulateRecords(array $migration, array $record): array
    {
        if (!isset($migration['create']) || !is_array($migration['create'])) {
            throw new Exception('Invalid migration array: "create" key is missing or not an array.');
        }

        $formArray = [];
        foreach ($migration['create'] as $columnName => $columnDefinition) {
            // Extract column information (name and type)
            $columnName = trim($columnName); // Remove extra spaces
            $type = self::extractColumnType($columnDefinition);

             //Handle the special case of 'id'
            if(strtolower($columnName) == 'id'){
                continue; // Skip 'id' column,
            }

            if ($type === null) {
                continue; // Skip if the type is not recognized.
                //throw new Exception("Could not determine column type for column: $columnName"); //Or you can throw exception
            }

            $label = ucwords(str_replace('_', ' ', $columnName)); // Generate user-friendly label
            $formArray[$label] = [
                'name' => $columnName,
                'placeholder' => "Enter $label", // Basic placeholder
                'type' => self::mapColumnTypeToFormType($type),
                'required' => self::isColumnRequired($columnDefinition) ? 'required' : '',
                'value' => $record[$columnName] ?? '', // Populate with record value if it exists
            ];

            // Add more specific form field attributes based on the column definition.
            if (stripos($columnDefinition, 'UNIQUE') !== false) {
                $formArray[$label]['unique'] = 'unique';
            }
            if (stripos($columnDefinition, 'ENUM') !== false)
            {
                $matches = [];
                preg_match('/ENUM\((.*?)\)/', $columnDefinition, $matches);
                if(isset($matches[1])){
                    $enums = explode(",", $matches[1]);
                    $formArray[$label]['options'] = array_map(function($v){ return trim($v, " '"); }, $enums);
                    $formArray[$label]['type'] = 'select'; //set the type
                }
            }
            if ($type == 'DECIMAL') {
                $formArray[$label]['type'] = 'number';
                $formArray[$label]['step'] = '0.01';
            }
            if ($type == 'INT')
            {
                 $formArray[$label]['type'] = 'number';
                 $formArray[$label]['step'] = '1';
            }
            if($type == 'TIMESTAMP' || $type == 'DATETIME') {
                $formArray[$label]['type'] = 'datetime-local';
            }
            if ($type == 'TEXT' || $type == 'LONGTEXT')
            {
                $formArray[$label]['type'] = 'textarea';
            }
        }
        return $formArray;
    }

    /**
     * Extracts the column type from the column definition string.
     *
     * @param string $columnDefinition The column definition string (e.g., 'VARCHAR(255)', 'INT(100)').
     * @return string|null The column type (e.g., 'VARCHAR', 'INT', 'DECIMAL', 'TEXT', 'TIMESTAMP') or null if not found.
     */
    private static function extractColumnType(string $columnDefinition): ?string
    {
        if (preg_match('/^(\w+)(?:\(\d+(?:,\s*\d+)?\))?/', $columnDefinition, $matches)) {
            return strtoupper($matches[1]); // Return in uppercase
        }
        return null;
    }

    /**
     * Maps a database column type to an HTML form input type.
     *
     * @param string $columnType The database column type (e.g., 'VARCHAR', 'INT', 'TEXT').
     * @return string The corresponding HTML form input type (e.g., 'text', 'number', 'textarea').
     */
    private static function mapColumnTypeToFormType(string $columnType): string
    {
        $typeMap = [
            'VARCHAR' => 'text',
            'INT' => 'number',
            'DECIMAL' => 'number',
            'TEXT' => 'textarea',
            'LONGTEXT' => 'textarea',
            'TIMESTAMP' => 'datetime-local',
            'DATETIME' => 'datetime-local',
            'DATE' => 'date',
            'ENUM' => 'select', //added enum
        ];
        return $typeMap[$columnType] ?? 'text'; // Default to 'text' if not found
    }

    /**
     * Determines if a column is required based on its definition.
     *
     * @param string $columnDefinition The column definition string.
     * @return bool True if the column is required, false otherwise.
     */
    private static function isColumnRequired(string $columnDefinition): bool
    {
        return stripos($columnDefinition, 'NOT NULL') !== false ||
               stripos($columnDefinition, 'PRIMARY KEY') !== false; //consider primary key as required
    }
}