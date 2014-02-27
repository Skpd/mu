<?php

require __DIR__ . '/vendor/autoload.php';

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$clients = new \SplObjectStorage();

$socket->on('connection', function ($conn) use ($clients) {
    echo 'Connected!' . PHP_EOL;
    $clients->attach($conn);

    $conn->on('data', function ($data) use ($clients, $conn) {
        \MuServer\Protocol\Debug::dump($data, 'Received: ');
    });

    $conn->on('end', function () use ($clients, $conn) {
        echo 'Disconnected!' . PHP_EOL;
        $clients->detach($conn);
    });

    $conn->write(new \MuServer\Protocol\Season097\ServerClient\JoinResult());
});

$socket->listen(55901, '0.0.0.0');
$loop->run();