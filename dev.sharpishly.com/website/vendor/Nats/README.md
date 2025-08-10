# NATS Order Processing Example (PHP)

This repository contains a PHP example demonstrating how to use NATS (Neural Autonomic Transport System) for order processing. It showcases publishing and subscribing to messages for handling customer orders.

## Overview

This example simulates an order processing system where:

1.  **Publisher:** A script publishes 100 customer order details to a NATS subject (`orders.process`).
2.  **Subscriber:** A separate script subscribes to the `orders.process` subject, simulates order processing (CRM, payment), and publishes a response message.
3.  **Callbacks:** The publisher uses callbacks to receive and handle order processing responses.
4.  **Asynchronous Processing:** The subscriber runs in the background, allowing for asynchronous order processing.

## Requirements

* PHP (with the `pcntl` extension for background processing on Unix-like systems)
* A running NATS server (default: `127.0.0.1:4222`)
* The `Nats.php` class (included in this repository)

## Installation

1.  **Clone the repository:**

    ```bash
    git clone <repository_url>
    cd <repository_directory>
    ```

2.  **Ensure NATS Server is Running:**

    Make sure you have a NATS server running. If you don't have one installed, you can find installation instructions on the official NATS website.

## Usage

1.  **Run the script:**

    ```bash
    php order_processing.php
    ```

2.  **Observe the output:**

    The script will output order processing results to the console. The subscriber runs in the background, and the publisher displays confirmations or errors as responses are received.

## Code Structure

* **`Nats.php`:** Contains the custom NATS client class.
* **`order_processing.php`:** The main script that includes the publisher and subscriber logic.

## Key Concepts

* **Publish-Subscribe (Pub/Sub):** The publisher publishes order details, and the subscriber receives them.
* **Request-Reply:** The publisher uses a `replyTo` subject to receive responses from the subscriber.
* **Asynchronous Processing:** The subscriber processes orders in the background.
* **Callbacks:** Callbacks are used to handle responses.
* **JSON Encoding:** JSON is used for message payloads.
* **`pcntl_fork()`:** Used to create a child process for the subscriber (Unix-like systems).
* **`wait()`:** Waits for all callbacks to finish.

## Notes

* **`pcntl_fork()`:** The `pcntl_fork()` function is only available on Unix-like systems (Linux, macOS). If you're on Windows, you'll need to use a different approach for running the subscriber in the background (e.g., separate terminal window, background process manager).
* **NATS Server:** Ensure that a NATS server is running on `127.0.0.1:4222`.
* **Error Handling:** Implement more robust error handling and logging for production environments.
* **Real Processing:** Replace the simulated order processing logic with your actual CRM and payment system integrations.
* **Timeout:** Adjust the timeout in the publisher to suit your needs.
* **Resource Management:** For a very large number of orders, consider adding resource management and batching techniques.
* **Nats.php** This class is intended for demonstration purposes. For production use, please use the official Nats PHP library.

## Contributing

Feel free to contribute to this project by submitting pull requests or opening issues.