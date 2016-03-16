<?php

use MuServer\Protocol\Debug;

require __DIR__ . '/vendor/autoload.php';

function csConnect($ip, $port) {
    echo "Connecting to $ip:$port\n";

    $loop = React\EventLoop\Factory::create();

    $dnsResolverFactory = new React\Dns\Resolver\Factory();
    $dns = $dnsResolverFactory->createCached('8.8.8.8', $loop);

    $connector = new \React\SocketClient\Connector($loop, $dns);

    $connector->createSocketForAddress($ip, $port)->then(function (React\Stream\Stream $stream) {
        echo 'Connected to the CS!' . PHP_EOL;

        $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerList());


        $stream->on('data', function ($data) use ($stream) {
            \MuServer\Protocol\Debug::dump($data, 'Received: ');
            $packet = \MuServer\Protocol\Season097\ServerClient\Factory::buildPacket($data);

            if ($packet instanceof \MuServer\Protocol\Season097\ServerClient\Handshake) {
                $stream->write(new \MuServer\Protocol\Season097\ClientServer\ServerList());
            } elseif ($packet instanceof \MuServer\Protocol\Season097\ServerClient\ServerList) {
                $result = new \MuServer\Protocol\Season097\ClientServer\ServerInfo('', false);

                $result->setServerCode(0);
                $result->setServerGroup(0);

                $stream->write($result->buildPacket());
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
            if (strlen($data) === 0) {
                exit("Disconnected =\\\n");
            }

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
                    if ($packet->isSuccess()) {
                        $result = new \MuServer\Protocol\Season097\ClientServer\LoginRequest('', false);
//                    $result->setLogin('dmskpd');
//                    $result->setPassword('qwe');
//                    $result->setSerial('NeSePraviNaHaker');
//                    $result->setVersion('09704');
                        $result->setLogin('skpd');
                        $result->setPassword('qwe');
//                    $result->setSerial('FIRSTPHPMUSERVER');
                        $result->setSerial('DarksTeam97d99i+');
                        $result->setVersion('09704');
                        $result->setTick(microtime(1) * 1000);

                        $stream->write($result->buildPacket());
                    } else {
                        exit("Login failed");
                    }
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

mu_decoder_init("data/Enc1.dat", "data/Dec2.dat");
//csConnect('156.24.1.36', 44405);
//csConnect('127.0.0.1', 44405);
csConnect('192.168.1.101', 44405);

//$head = $sub = null;
//$packet = implode('', array_map('hex2bin', str_split('C105F10300', 2)));
////$packet = implode('', array_map('hex2bin', str_split('C30D7BF80E8B5A62F34B686059', 2)));
//
//mu_decoder_init("data/Enc1.dat", "data/Dec2.dat");
//Debug::dump($packet, 'before');
//$packet = mu_encode_c3($packet, $head, $sub);
//Debug::dump($packet, 'after');
//
//mu_decoder_init("data/Enc2.dat", "data/Dec1.dat");
//$packet = mu_decode_c3($packet, $head, $sub);
//Debug::dump($packet, 'decoded');
