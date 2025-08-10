<?php

// background_worker.php

// This script will run in the background, continuously processing tasks.

// Function to simulate a task (replace with your actual task logic)
function processTask($taskData) {
    echo "Processing task: " . json_encode($taskData) . "\n";
    // Simulate some work (e.g., database operation, API call)
    sleep(2); // Simulate 2 seconds of work
    echo "Task processed.\n";
}

// Infinite loop to keep the worker running
while (true) {
    // Check for tasks (e.g., from a queue, database, or NATS)
    $task = getNextTask(); // Replace with your task retrieval logic

    if ($task !== null) {
        processTask($task);
    } else {
        // No tasks available, sleep for a short time
        sleep(1); // Check again in 1 second
    }
}

// Function to get the next task (replace with your actual task retrieval)
function getNextTask() {
    // Example: Read tasks from a file (replace with your data source)
    $taskFile = 'tasks.json';
    if (file_exists($taskFile)) {
        $tasks = json_decode(file_get_contents($taskFile), true);
        if (!empty($tasks)) {
            $task = array_shift($tasks); // Get the first task
            file_put_contents($taskFile, json_encode($tasks)); // Update the task file
            return $task;
        }
    }
    return null; // No tasks available
}

?>