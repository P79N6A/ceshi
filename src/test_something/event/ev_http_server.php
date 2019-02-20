<?php
$connections = [];
$events      = [];
$socket      = stream_socket_server('tcp://0.0.0.0:9800');
stream_set_blocking($socket, 0);
$event = new EvIo($socket, Ev::READ, function ($watcher, $events) use ($socket) {
    global $connections, $events;
    $client_socket = stream_socket_accept($socket, 0, $remote_address);
    stream_set_blocking($client_socket, 0);
    $client_event                     = new EvIo($client_socket, Ev::READ,
        function ($watcher, $events) use ($client_socket) {
            global $connections, $events;
            fread($client_socket, 65535);
            $content     = '<h1>It Works!</h1>';
            $content_len = strlen($content);
            $response    = <<<EOL
HTTP/1.1 200 OK
Content-Type: text/html; charset=UTF-8
Connection: keep-alive
Content-Length: $content_len
EOL;
            $response .= "\r\n" . $content;
            fwrite($client_socket, $response);
            fclose($client_socket);
            unset($connections[(int)$client_socket]);
            unset($events[(int)$client_socket]);
        });
    $events[(int)$client_socket]      = $client_event;
    $connections[(int)$client_socket] = $client_socket;
});
Ev::run();