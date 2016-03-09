<?php

mu_decoder_init("data/Enc2.dat", "data/Dec1.dat");

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
            $char1->setCharClass(\MuServer\Protocol\Season097\ServerClient\CharList::CLASS_MG);
            $char1->setSet(
                  chr(0xFF)   // right arm (weapon)
                . chr(0xFF)   // left arm (weapon / shield)

                . chr(0xFF)   // helm and armor type
                . chr(0xFF)   // gloves and pants type
                . chr(0xFF)   // boots and wings type

                . chr(0x00)   // boots and gloves level
                . chr(0x00)   // pants armor helm gloves level
                . chr(0x00)   // helm level

                . chr(0xF8)   // 2nd wings ?

                . chr(0x00)   // is exc ? 1 << 1 | 1 << 2 ... 1 << 7
            );

            $char2->setName('asdfghjkl;');
            $char2->setIndex(4);
            $char2->setCharClass(\MuServer\Protocol\Season097\ServerClient\CharList::CLASS_ME);

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