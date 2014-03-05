<?php

require __DIR__ . '/vendor/autoload.php';

use MuServer\Protocol\Season097\ClientServer\Factory as Factory;
use MuServer\Protocol\Season097\ClientServer\LoginRequest as CLoginRequest;
use MuServer\Protocol\Season097\ServerClient\LoginResult as SLoginResult;

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$clients = new \SplObjectStorage();
$players = new \SplObjectStorage();

$socket->on('connection', function ($conn) use ($clients, $players) {
    echo 'Connected!' . PHP_EOL;
    $clients->attach($conn);

    $conn->on('data', function ($data) use ($clients, $conn, $players) {
        \MuServer\Protocol\Debug::dump($data, 'Received: ');
        $packet = Factory::buildPacket($data);

        if ($packet instanceof CLoginRequest) {
            $players->attach($packet);

            $result = new SLoginResult(SLoginResult::RESULT_BAD_PASSWORD);
            $conn->write($result);
        }
    });

    $conn->on('end', function () use ($clients, $conn) {
        echo 'Disconnected!' . PHP_EOL;
        $clients->detach($conn);
    });

    $conn->write(new \MuServer\Protocol\Season097\ServerClient\JoinResult());
});

$socket->listen(55901, '0.0.0.0');
$loop->run();