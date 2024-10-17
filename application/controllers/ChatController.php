<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ChatController extends CI_Controller {

    public function sendMessage() {

        $status = 'error';
        $response = 'Something went wrong plz try again!';
        if($this->input->method(true)=='POST'){

            $data = '51_1_2_3';
            // $data = array('EID' => 51, 'BillId' => 1);
            // $data = json_encode($data);

            $status = 'success';
            // $response = $messageId;

                header('Content-Type: application/json');
                echo json_encode(array(
                    'status' => $status,
                    'response' => $data
                  ));
                 die;
        }

        // Notify the WebSocket server
        // $this->notifyWebSocketServer(1, $messageData['Name1'], $messageId, 2);
    }

    private function notifyWebSocketServer($receiverId, $message, $messageId, $senderId) {
        $wsServerUrl = "ws://localhost:8080";
        $data = json_encode([
            'receiver_id' => $receiverId,
            'message' => $message,
            'message_id' => $messageId,
            'sender_id' => $senderId
        ]);

        // Send notification to WebSocket server
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $wsServerUrl);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);

        echo "<pre>";
        print_r($response);die;
        curl_close($ch);
    }

}