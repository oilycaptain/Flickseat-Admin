<?php
$server = new swoole_websocket_server("0.0.0.0", 8080);
$server->on("message", function($ws, $frame) {
    foreach ($ws->connections as $conn) {
        $ws->push($conn, "update");
    }
});
$server->start();
?>
