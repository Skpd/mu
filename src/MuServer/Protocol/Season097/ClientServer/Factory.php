<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Protocol\Debug;
use MuServer\Security;

class Factory
{
    private static $knownPackets = [
        "Cclass/Clength/Ccode/CsubCode",
        "Cclass/Clength/Ccode",
    ];

    public static function buildPacket($rawData)
    {
        $class = ord($rawData[0]);
        $head  = $sub = 0;

        if ($class === 0xC1) {
            Security::extract($rawData);
            $head = ord($rawData[2]);
            $sub  = ord($rawData[3]);

            if ($head === 0xF4) {
                if ($sub === 0x03) {
                    return new ServerInfo(substr($rawData, 4));
                } elseif ($sub === 0x02) {
                    return new ServerList(substr($rawData, 4));
                }
            }

            if ($head === 0xF3) {
                if ($sub === 0x00) {
                    return new CharListRequest;
                } elseif ($sub === 0x03) {
                    return new MapJoinRequest(substr($rawData, 4));
                } elseif ($sub === 0x01) {
                    return new CreateCharacter(substr($rawData, 4));
                } elseif ($sub === 0x02) {
                    return new DeleteCharacter(substr($rawData, 4));
                } elseif ($sub === 0x06) {
                    return new AddPoint(substr($rawData, 4));
                }
            }

            if ($head === 0xF1) {
                if ($sub === 0x02) {
                    return new ClientClose(substr($rawData, 4));
                }
            }
        }

        if ($class === 0xC3) {
            $rawData = mu_decode_c3($rawData, $class, $head, $sub);
            Debug::dump($rawData, 'Decoded: ');
            if (($head === 0xF1 || $head === 0xFA) && ($sub === 0x01 || $sub === 0x00)) {
                return new LoginRequest(substr($rawData, 4));
            }

            if ($head === 0xF1 && $sub === 0x03) {
                return new ClientClose(substr($rawData, 4));
//                return new CheckSum(substr($rawData, 4));
            }

            if ($head === 0x03 && $sub == 0x00) {
                return new CheckSum(substr($rawData, 4));
            }

            if ($head === 0x0E) {
                return new Ping(substr($rawData, 3));
            }
        }

        throw new \RuntimeException("Unexpected packet '" . Debug::dump($rawData, null, true) . "'");
    }
}