<?php

namespace GitHub;

class GitHubActions {
    private $workflowName;
    private $workflowConfig;
    private $envVars;

    public function __construct($name = 'CI Workflow') {
        $this->workflowName = $name;
        $this->workflowConfig = [
            'name' => $name,
            'on' => [],
            'jobs' => []
        ];
        $this->envVars = $_ENV; // Capture GitHub Actions environment variables
    }

    /**
     * Set trigger for push events
     */
    public function onPush($branches = ['main']) {
        $this->workflowConfig['on']['push'] = [
            'branches' => $branches
        ];
        return $this;
    }

    /**
     * Set trigger for pull request events
     */
    public function onPullRequest($branches = ['main']) {
        $this->workflowConfig['on']['pull_request'] = [
            'branches' => $branches
        ];
        return $this;
    }

    /**
     * Add a job to the workflow
     */
    public function addJob($jobId, $name, $runsOn = 'ubuntu-latest') {
        $this->workflowConfig['jobs'][$jobId] = [
            'name' => $name,
            'runs-on' => $runsOn,
            'steps' => []
        ];
        return $this;
    }

    /**
     * Add a step to a specific job
     */
    public function addStep($jobId, $name, $run = null, $uses = null) {
        if (!isset($this->workflowConfig['jobs'][$jobId])) {
            throw new \Exception("Job '$jobId' not found");
        }

        $step = ['name' => $name];
        if ($run) $step['run'] = $run;
        if ($uses) $step['uses'] = $uses;

        $this->workflowConfig['jobs'][$jobId]['steps'][] = $step;
        return $this;
    }

    /**
     * Set environment variables for a job
     */
    public function setJobEnv($jobId, $env = []) {
        if (!isset($this->workflowConfig['jobs'][$jobId])) {
            throw new \Exception("Job '$jobId' not found");
        }
        $this->workflowConfig['jobs'][$jobId]['env'] = $env;
        return $this;
    }

    /**
     * Get current GitHub Actions environment variable
     */
    public function getEnv($key) {
        return isset($this->envVars[$key]) ? $this->envVars[$key] : null;
    }

    /**
     * Generate YAML workflow configuration
     */
    public function generateWorkflowYAML() {
        return $this->arrayToYAML($this->workflowConfig);
    }

    /**
     * Helper method to convert array to YAML
     */
    private function arrayToYAML($array, $indent = 0) {
        $lines = [];
        $spaces = str_repeat(' ', $indent);

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (empty($value)) {
                    $lines[] = "$spaces$key: []";
                } else {
                    if (array_keys($value) === range(0, count($value) - 1)) { 
                        // It's a list (numeric keys)
                        foreach ($value as $listItem) {
                            if (is_array($listItem)) {
                                $lines[] = "$spaces-";
                                $lines[] = $this->arrayToYAML($listItem, $indent + 2);
                            } else {
                                $lines[] = "$spaces- " . $this->formatValue($listItem);
                            }
                        }
                    } else {
                        // It's an associative array (map)
                        $lines[] = "$spaces$key:";
                        $lines[] = $this->arrayToYAML($value, $indent + 2);
                    }
                }
            } else {
                $lines[] = "$spaces$key: " . $this->formatValue($value);
            }
        }

        return implode("\n", $lines);
    }

    /**
     * Format values correctly for YAML output
     */
    private function formatValue($value) {
        if (is_bool($value)) {
            return $value ? "true" : "false";
        }
        return is_string($value) ? "'$value'" : $value;
    }

    /**
     * Check if running in GitHub Actions environment
     */
    public function isGitHubActions() {
        return $this->getEnv('GITHUB_ACTIONS') === 'true';
    }

    /**
     * Save YAML to a file
     */
    public function saveToFile($filename) {
        //TODO: Provide path on GitHub
        //file_put_contents($filename, $this->generateWorkflowYAML());
    }
}

// Example Usage:




