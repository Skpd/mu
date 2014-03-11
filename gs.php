<?php

require __DIR__ . '/vendor/autoload.php';

use MuServer\Protocol\Season097\ClientServer\CharListRequest as CCharListRequest;
use MuServer\Protocol\Season097\ClientServer\Ping as CPing;
use MuServer\Protocol\Season097\ClientServer\Factory as Factory;
use MuServer\Protocol\Season097\ClientServer\LoginRequest as CLoginRequest;
use MuServer\Protocol\Season097\ServerClient\CharListResult as SharListResult;
use MuServer\Protocol\Season097\ServerClient\LoginResult as SLoginResult;

$loop = React\EventLoop\Factory::create();
$socket = new React\Socket\Server($loop);

$clients = new \SplObjectStorage();
$players = new \SplObjectStorage();

$socket->on('connection', function ($conn) use ($clients, $players) {
    echo 'Connected!' . PHP_EOL;
    $clients->attach($conn);

    $conn->on('data', function ($data) use ($clients, $conn, $players) {
        /** @var \React\Socket\Connection $conn */

        \MuServer\Protocol\Debug::dump($data, 'Received: ');

        $packet = Factory::buildPacket($data);

        if ($packet instanceof CLoginRequest) {
            $players->attach($packet);

            $result = new SLoginResult(SLoginResult::SUCCESS);
            $conn->write($result);
            return;
        }

        if ($packet instanceof CPing) {
            printf(
                "Client: %s. Tick: %u, pAtkSpd: %u, mAtkSpd: %u, UNKNOWN: %02X\n",
                $conn->getRemoteAddress(),
                $packet->getTick(), $packet->getPAttackSpeed(), $packet->getMAttackSpeed(), $packet->getUnknown()
            );
        }

        if ($packet instanceof CCharListRequest) {
            $result = new SharListResult(1, "skpd");
            $conn->write($result);

            $char1 = new \MuServer\Protocol\Season097\ServerClient\CharList();
            $char2 = new \MuServer\Protocol\Season097\ServerClient\CharList();

            $char1->setName('qwertyuiop');
            $char1->setIndex(0);

            $char2->setName('asdfghjkl;');
            $char2->setIndex(1);

            $result = new \MuServer\Protocol\Season097\ServerClient\CharListCount([$char1, $char2]);
            $conn->write($result);
            return;
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