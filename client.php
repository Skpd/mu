<?php

require __DIR__ . '/vendor/autoload.php';

function csConnect($ip, $port) {
    $loop = React\EventLoop\Factory::create();

    $dnsResolverFactory = new React\Dns\Resolver\Factory();
    $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

    $connector = new \React\SocketClient\Connector($loop, $dns);

    $connector->createSocketForAddress($ip, $port)->then(function (React\Stream\Stream $stream) {
        echo 'Connected to the CS!' . PHP_EOL;
        $stream->on('data', function ($data) use ($stream) {
            \MuServer\Protocol\Debug::dump($data, 'Received: ');
            $packet = \MuServer\Protocol\Debug::dump($data, null, 1);

            switch ($packet) {
                case 'C1040001':
                    $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerList());
                    break;

                case substr($packet, 0, -4) === 'C2000AF402010000':
                    $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerInfo(chr(0) . chr(0)));
                    break;

                case 'C116F40336322E3130362E3130342E32343000005DDA':
                    $stream->end();
                    gsConnect(trim(substr($data, 4, 16)), unpack('v', substr($data, -2, 2))[1]);
                    break;
            }
        });
    });

    $loop->run();
}

function gsConnect($ip, $port) {
    $loop = React\EventLoop\Factory::create();

    $dnsResolverFactory = new React\Dns\Resolver\Factory();
    $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

    $connector = new \React\SocketClient\Connector($loop, $dns);

    $connector->createSocketForAddress($ip, $port)->then(function (React\Stream\Stream $stream) {
        echo 'Connected to the GS!' . PHP_EOL;
        $stream->on('data', function ($data) use ($stream) {
            \MuServer\Protocol\Debug::dump($data, 'Received: ');
            $packet = \MuServer\Protocol\Debug::dump($data, null, 1);
            $stream->write(new \MuServer\Protocol\Season097\ServerClient\JoinResult());
//            $stream->end();
        });
    });

    $loop->run();
}

//csConnect('78.28.197.110', 44405);
//csConnect('62.106.104.240', 44405);
csConnect('127.0.0.1', 44405);