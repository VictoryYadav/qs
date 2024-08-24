<?php
use Ratchet\Http\HttpServer;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;

// require dirname(__DIR__) . '/vendor/autoload.php';
// require dirname(__DIR__) . '/application/controllers/WebSocketServer.php';


include_once './vendor/autoload.php';
include_once './application/controllers/WebSocketServer.php';
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketServer()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";

$server->run();
