<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

// include_once  './vendor/autoload.php';
require_once APPPATH . './vendor/autoload.php';
require dirname(__DIR__) . '/controllers/WebSocketServer.php';

class RunWebSocket extends CI_Controller {

    public function index() {
        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new WebSocketServer()
                )
            ),
            8080 // Port number
        );

        $server->run();
    }
}


