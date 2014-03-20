<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Protocol\Debug;

class Factory
{
    public static function buildPacket($rawData)
    {
        $class = ord($rawData[0]);

        if ($class == 0xC1) {
            $head = ord($rawData[2]);
            $sub  = ord($rawData[3]);

            if ($head == 0) {
                return Handshake::buildFrom(substr($rawData, 3));
            }

            if ($head == 0xF4) {
                if ($sub == 3) {
                    return ServerInfo::buildFrom(substr($rawData, 4));
                }
            }

            if ($head == 0xF1) {
                if ($sub == 0) {
                    return LoginResult::buildFrom(substr($rawData, 4));
                }
            }
        }

        if ($class == 0xC2) {
            $head = ord($rawData[3]);
            $sub  = ord($rawData[4]);

            if ($head === 0xF4) {
                if ($sub === 0x02) {
                    return ServerList::buildFrom(substr($rawData, 5));
                }
            }
        }

        throw new \RuntimeException("Unexpected packet '" . Debug::dump($rawData, null, true) . "'");
    }
}