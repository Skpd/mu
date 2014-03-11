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

                case substr($packet, 0, 4) === 'C200':
                    $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerInfo(chr(0) . chr(0)));
                    break;

                case substr($packet, 0, 8) === 'C116F403':
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

        $stream->write(implode('', array_map('chr', [0xC3, 0x44, 0xAD, 0xF8, 0x00, 0xFA, 0x8E, 0x3C, 0x50, 0x10, 0xEC, 0xA7, 0x92, 0x83, 0x3E, 0x49, 0xDE, 0x93, 0xC6, 0x13, 0xDA, 0x88, 0x83, 0xB6, 0x9B, 0x04, 0x3A, 0x4B, 0x96, 0x27, 0xF1, 0xA1, 0x04, 0x44, 0x71, 0xCE, 0x18, 0x16, 0x1A, 0xA8, 0x0D, 0x70, 0xF5, 0x55, 0xA9, 0x9C, 0x28, 0xF6, 0x71, 0x9D, 0xC4, 0x30, 0xC3, 0x66, 0xE8, 0xED, 0xD8, 0xD3, 0xE5, 0x0E, 0x43, 0xCE, 0x13, 0x90, 0x36, 0x15, 0xDD, 0xE8])));

        $stream->on('data', function ($data) use ($stream) {
            \MuServer\Protocol\Debug::dump($data, 'Received: ');
            $packet = \MuServer\Protocol\Debug::dump($data, null, 1);

            if ($packet == "C105F10101") {
                $stream->write(new \MuServer\Protocol\Season097\ClientServer\CharListRequest());
            }
//            $stream->end();
        });
    });

    $loop->run();
}

//csConnect('78.28.197.110', 44405);
//csConnect('62.106.104.240', 44405);
csConnect('82.135.154.56', 44405);
//csConnect('127.0.0.1', 44405);
//csConnect('192.168.1.105', 44405);