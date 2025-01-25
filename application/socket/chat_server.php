<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);

// use Ratchet\Server\IoServer;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;

use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\IoServer;
use Ratchet\Server\SecureServer;
use React\EventLoop\Factory;
use React\Socket\SocketServer;


// // require dirname(__DIR__) . '/vendor/autoload.php';
// // require dirname(__DIR__) .'/application/controllers/Chat.php';

// // for local
include_once './vendor/autoload.php';
include_once './application/controllers/Chat.php';

// SSL context configuration
// $sslContext = stream_context_create([
//     'ssl' => [
//         'local_cert' => '/root/vtrend.org.pem', // Path to SSL certificate
//         'local_pk'   => '/root/vtrend.org.key',   // Path to private key
//         'allow_self_signed' => false,  // Disable self-signed certificates in production
//         'verify_peer' => false         // Disable peer verification for simplicity
//     ]
// ]);

// $server = IoServer::factory(
//     new HttpServer(
//         new WsServer(
//             new Chat()
//         )
//     ),
//     8080
// );

// echo "WebSocket server started on port 8080\n";

// $server->run();



$loop = Factory::create();

// Create the SSL context
$sslContext = [
    'local_cert' => '/root/vtrend.org.pem', // Path to SSL certificate
    'local_pk'   => '/root/vtrend.org.key', // Path to private key
    'allow_self_signed' => false,          // Disable self-signed certificates in production
    'verify_peer' => false                 // Disable peer verification for simplicity
];

// Create a secure ReactPHP server
$secureSocket = new SocketServer('0.0.0.0:8080', [
    'tls' => $sslContext,
], $loop);

// Create the WebSocket server
$server = new IoServer(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    $secureSocket,
    $loop
);

// Start the server
echo "WebSocket server running on wss://0.0.0.0:8080\n";
// $server->run();
$loop->run();
