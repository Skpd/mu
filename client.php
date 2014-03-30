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
            $packet = \MuServer\Protocol\Season097\ServerClient\Factory::buildPacket($data);

            if ($packet instanceof \MuServer\Protocol\Season097\ServerClient\Handshake) {
                $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerList());
            } elseif ($packet instanceof \MuServer\Protocol\Season097\ServerClient\ServerList) {
                $result = new \MuServer\Protocol\Season097\ClientServer\ServerInfo('');

                $result->setServerCode($packet->getServers()[0]->getCode());
                $result->setServerGroup($packet->getServers()[0]->getGroup());

                $stream->write($result);
            } elseif ($packet instanceof \MuServer\Protocol\Season097\ServerClient\ServerInfo) {
                $stream->end();

                gsConnect($packet->getServer()->getIp(), $packet->getServer()->getPort());
            }
        });
    });

    $loop->run();
}

function gsConnect($ip, $port) {
    echo "Connecting to $ip:$port\n";
    $loop = React\EventLoop\Factory::create();

    $dnsResolverFactory = new React\Dns\Resolver\Factory();
    $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

    $connector = new \React\SocketClient\Connector($loop, $dns);

    $connector->createSocketForAddress($ip, $port)->then(function (React\Stream\Stream $stream) {
        echo 'Connected to the GS!' . PHP_EOL;

        $stream->on('data', function ($data, $connection) use ($stream) {
            if (ord($data[0]) == 0xC1) {
                if (strlen($data) != ord($data[1])) {
                    $packet = substr($data, 0, ord($data[1]));
                    $stop = false;
                    $next = substr($data, ord($data[1]));
                } else {
                    $packet = $data;
                    $stop = true;
                }

                try {
                    \MuServer\Protocol\Debug::dump($packet, 'Received: ');
                    $packet = \MuServer\Protocol\Season097\ServerClient\Factory::buildPacket($packet);
                } catch (\RuntimeException $e) {
                    var_dump($e->getMessage(), $data);
                    return;
                }

                if ($packet instanceof \MuServer\Protocol\Season097\ServerClient\LoginResult) {
                    if ($packet->getResult() === \MuServer\Protocol\Season097\ServerClient\LoginResult::SUCCESS) {
                        echo "Login OK. Getting char list...\n";
                        $stream->write(new \MuServer\Protocol\Season097\ClientServer\CharListRequest());
                    } else {
                        switch ($packet->getResult()) {
                            case \MuServer\Protocol\Season097\ServerClient\LoginResult::NEW_VERSION_REQUIRED:
                                echo "New version is required!\n";
                                break;
                            default:
                                var_dump($packet->getResult());
                                break;
                        }
                        $stream->close();
                    }
                } else if ($packet instanceof \MuServer\Protocol\Season097\ServerClient\CharListCount) {
                    echo "Chars: \n";
                    foreach ($packet->getChars() as $char) {
                        echo "{$char->getName()} [{$char->getLevel()}]\n";
                    }
                    $result = new \MuServer\Protocol\Season097\ClientServer\MapJoinRequest('');
                    $result->setName(current($packet->getChars())->getName());
                    echo "Selecting '{$result->getName()}'\n";
                    sleep(1);
                    $stream->write($result);
                } else if ($packet instanceof \MuServer\Protocol\Season097\ServerClient\JoinResult) {
                    $result = new \MuServer\Protocol\Season097\ClientServer\LoginRequest('');
                    $result->setLogin('dmskpd');
                    $result->setPassword('yt3frjyyj');
                    $result->setSerial('DarksTeam97d99i+');
                    $result->setVersion('09704');
//                    $result->setLogin('skpd');
//                    $result->setPassword('qwe');
//                    $result->setSerial('FIRSTPHPMUSERVER');
//                    $result->setVersion('09700');
                    $result->setTick(round(substr(microtime(1), 4) * 1000));

                    $stream->write($result->buildPacket());
                }
//var_dump($packet);
                if (!$stop) {
                    $stream->emit('data', [$next, $connection]);
                }
            }
        });

        $stream->on('end', function () {
            echo "Disconnected from GS!\n";
        });
    });

    $loop->run();
}

//csConnect('78.28.197.110', 44405);
//csConnect('62.106.104.240', 44405);
//csConnect('82.135.154.56', 44405);
//csConnect('127.0.0.1', 44405);
//csConnect('192.168.1.105', 44405);
csConnect('94.70.23.245', 44405);