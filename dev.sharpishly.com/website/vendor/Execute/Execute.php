<?php

namespace Execute;

class Execute {
    private $command;

    public function __construct($command) {
        $this->command = $command;
    }

    public function get() {
        try {
            $output = array();
            exec($this->command, $output);
            return $output;
        } catch (\Exception $e) {
            echo "Error executing command: " . $e->getMessage() . "\n";
            return null;
        }
    }
}

?>