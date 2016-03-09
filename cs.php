<?php
namespace MuServer;

mu_decoder_init("data/Enc2.dat", "data/Dec1.dat");

require __DIR__ . '/vendor/autoload.php';

use MuServer\Protocol\Season097\ServerClient\ServerList as SServerList;
use MuServer\Protocol\Season097\ClientServer\Factory as Factory;
use MuServer\Protocol\Season097\ClientServer\ServerList as CServerList;
use MuServer\Protocol\Season097\ServerClient\ServerInfo as SServerInfo;
use MuServer\Protocol\Season097\ClientServer\ServerInfo as CServerInfo;
use MuServer\Protocol\Season097\ServerClient\Handshake as SHandshake;

$loop = \React\EventLoop\Factory::create();
$socket = new \React\Socket\Server($loop);

$clients = new \SplObjectStorage();

$socket->on('connection', function ($stream) use ($clients) {
    /** @var $stream \React\Stream\Stream */
    echo 'Connected!' . PHP_EOL;

    $clients->attach($stream);

    $serverList = new SServerlist;

    $server = new Server(0, 0);
    $server->setIp('127.0.0.1');
    $serverList->addServer($server);

    $stream->on('data', function ($data) use ($clients, $stream, $serverList) {
        Protocol\Debug::dump($data, 'Received: ');
        $packet = Factory::buildPacket($data);

        if ($packet instanceof CServerList) {
            $stream->write($serverList);
        } else if ($packet instanceof CServerInfo) {
            $info = new SServerInfo($serverList->findServer($packet));
            $stream->write($info);
        }
    });

    $stream->on('end', function () use ($clients, $stream) {
        echo 'Disconnected!' . PHP_EOL;
        $clients->detach($stream);
    });

//    $conn->write($serverList);
    $stream->write(new SHandshake());
});

$socket->listen(44405, '0.0.0.0');
$loop->run();