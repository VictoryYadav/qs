<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class WebSocketServer implements MessageComponentInterface {

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients[$conn->resourceId] = $conn;
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        // Here you would process incoming messages and potentially query the database
        $CI =& get_instance();  // Get CodeIgniter instance
        $CI->load->database();

        $query = $CI->db->query("SELECT * FROM your_table WHERE your_conditions");

        foreach ($query->result() as $row) {
            $data[] = $row;
        }

        // Send data back to the client
        $from->send(json_encode($data));
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection from the list
        unset($this->clients[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }
}

