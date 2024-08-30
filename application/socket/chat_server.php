<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;


require dirname(__DIR__) . '/vendor/autoload.php';
require dirname(__DIR__) .'/application/controllers/Chat.php';

// for local
// include_once './vendor/autoload.php';
// include_once './application/controllers/Chat.php';

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080\n";

$server->run();
