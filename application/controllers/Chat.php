<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg) {


        // echo "<pre>";
        // print_r($msg);die;
        
        foreach ($this->clients as $client) {
            if ($from !== $client) {
                $client->send($msg);
            }
        }
        
        // $data = json_decode($msg);

        // if (isset($data->receiver_id)) {
        //     foreach ($this->clients as $client) {
        //         if ($client->resourceId == $data->receiver_id) {
        //             $client->send(json_encode([
        //                 'message_id' => $data->message_id,
        //                 'message' => $data->message,
        //                 'sender_id' => $data->sender_id,
        //             ]));
        //         }
        //     }
        // }
    }

    public function onClose(ConnectionInterface $conn) {
        // Remove the connection on close
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
