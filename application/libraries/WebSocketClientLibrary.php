<?php
ini_set('max_execution_time', 300); // 300 seconds = 5 minutes
ini_set('default_socket_timeout', 60); // 60 seconds

include_once './vendor/autoload.php';

use WebSocket\Client;
use WebSocket\Exception\TimeoutException;


class WebSocketClientLibrary {
    private $client;

    public function __construct() {
        // Connect to WebSocket server
        // $this->client = new Client("ws://localhost:8080", ['timeout' => 60]);
    }

    public function sendMessage($message) {
        // $this->client->send($message);
        // // Wait and get the response from server
        // return $this->client->receive();

        try {
            $client = new Client("ws://localhost:8080", [
                'timeout' => 60 // Set a longer timeout
            ]);

            // Periodically send a ping to keep the connection alive
            while (true) {
                $client->send($message);
                $response = $client->receive();
                echo "Server response: $response\n";
                sleep(30); // Wait for 30 seconds before sending the next ping
            }

        } catch (TimeoutException $e) {
            echo "Client read timeout: " . $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "An error occurred: " . $e->getMessage() . "\n";
        }
die;


        $maxRetries = 3;
        $retryCount = 0;

        do {
            try {
                $client = new Client("ws://localhost:8080", [
                    'timeout' => 60 // Set a longer timeout
                ]);

                $client->send($message);

                $response = $client->receive();
                echo "Server response: $response\n";
                break; // Exit loop on success

            } catch (TimeoutException $e) {
                $retryCount++;
                echo "Client read timeout. Retrying... ($retryCount/$maxRetries)\n";
                sleep(5); // Wait before retrying
            } catch (Exception $e) {
                echo "An error occurred: " . $e->getMessage() . "\n";
                break;
            }
        } while ($retryCount < $maxRetries);

        if ($retryCount == $maxRetries) {
            echo "Failed to send message after $maxRetries attempts.\n";
        }
    }
    
}
