<?php
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);

// use Ratchet\Server\IoServer;
// use Ratchet\Http\HttpServer;
// use Ratchet\WebSocket\WsServer;


// // require dirname(__DIR__) . '/vendor/autoload.php';
// // require dirname(__DIR__) .'/application/controllers/Chat.php';

// // for local
// include_once './vendor/autoload.php';
// include_once './application/controllers/Chat.php';

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



error_reporting(E_ALL);
ini_set('display_errors', 1);

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use React\Socket\SocketServer;

// for local
include_once './vendor/autoload.php';
include_once './application/controllers/Chat.php';

// SSL context configuration
$sslContext = stream_context_create([
    'ssl' => [
        'local_cert' => '/root/vtrend.org.pem', // Path to SSL certificate
        'local_pk'   => '/root/vtrend.org.key',   // Path to private key
        'allow_self_signed' => false,  // Disable self-signed certificates in production
        'verify_peer' => false         // Disable peer verification for simplicity
    ]
]);

// Create React socket server with SSL
$socket = new SocketServer('tls://0.0.0.0:8080', $sslContext);

// Create Ratchet WebSocket server
$server = new IoServer(
    new HttpServer(
        new WsServer(
            new Chat()
        )
    ),
    $socket
);

echo "Secure WebSocket server started on wss://ws.dac.org:8080\n";

$server->run();



// use Ratchet\Http\HttpServer;
// use Ratchet\Server\IoServer;
// use Ratchet\WebSocket\WsServer;
// use React\EventLoop\Factory;
// use React\Socket\SecureServer;
// use React\Socket\Server;

// require dirname(__DIR__) . '/vendor/autoload.php';

// class Chat implements \Ratchet\MessageComponentInterface {
//     public function onOpen($conn) {
//         echo "New connection: ({$conn->resourceId})\n";
//     }

//     public function onMessage($from, $msg) {
//         echo "Message received: $msg\n";
//     }

//     public function onClose($conn) {
//         echo "Connection {$conn->resourceId} closed\n";
//     }

//     public function onError($conn, $e) {
//         echo "An error occurred: {$e->getMessage()}\n";
//         $conn->close();
//     }
// }

// $loop = Factory::create();

// // Create a standard HTTP server
// $webSocketServer = new Server('0.0.0.0:8080', $loop);

// // Secure the WebSocket server with SSL
// $secureWebSocketServer = new SecureServer($webSocketServer, $loop, [
//     'local_cert' => '/etc/letsencrypt/live/ws.dac.org/fullchain.pem', // Path to fullchain.pem
//     'local_pk' => '/etc/letsencrypt/live/ws.dac.org/privkey.pem',     // Path to privkey.pem
//     'allow_self_signed' => false,                                    // Set true for testing only
//     'verify_peer' => false                                           // Set true in production
// ]);

// $server = new IoServer(
//     new HttpServer(
//         new WsServer(
//             new Chat()
//         )
//     ),
//     $secureWebSocketServer,
//     $loop
// );

// echo "Secure WebSocket server running on wss://ws.dac.org:8080\n";

// $loop->run();
