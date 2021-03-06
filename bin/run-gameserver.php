<?php
namespace MuServer;

use MuServer\Protocol\Season097\ServerClient\ServerList as SServerList;
use MuServer\Protocol\Season097\ClientServer\Factory as Factory;
use MuServer\Protocol\Season097\ClientServer\ServerList as CServerList;
use MuServer\Protocol\Season097\ServerClient\ServerInfo as SServerInfo;
use MuServer\Protocol\Season097\ClientServer\ServerInfo as CServerInfo;
use MuServer\Protocol\Season097\ServerClient\Handshake as SHandshake;

chdir(dirname(__DIR__));
mu_decoder_init("data/Enc2.dat", "data/Dec1.dat");

require 'vendor/autoload.php';
$sm = include 'src/MuServer/bootstrap.php';

/** @var \React\EventLoop\LoopInterface $loop */
$loop = $sm->get('GameLoop');

//game server
/** @var \MuServer\Game\Server $gs */
$gs = $sm->get('GameServer');
$gs->init();
$regen = new Game\Event\Regen($gs->getPlayers());
$loop->addPeriodicTimer($regen->getInterval(), $regen->getCallback());


//connect server
$socket = new \React\Socket\Server($loop);
$clients = new \SplObjectStorage();
$socket->on('connection', function ($stream) use ($clients) {
    /** @var $stream \React\Stream\Stream */
    echo 'Connected!' . PHP_EOL;

    $clients->attach($stream);

    $serverList = new SServerlist;

    $server = new Server(0, 1);
    $server->setIp('83.243.67.215');
    $serverList->addServer($server);

    $stream->on('data', function ($data) use ($clients, $stream, $serverList) {
        Protocol\Debug::dump($data, 'Received: ');
        try {
            $packet = Factory::buildPacket($data);
        } catch (\Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            return;
        }

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

    $stream->write(new SHandshake());
});
$socket->listen(44405, '0.0.0.0');

$loop->run();

