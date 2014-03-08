<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Protocol\Debug;

class Factory
{
    private static $knownPackets = [
        "Cclass/Clength/Ccode/CsubCode",
        "Cclass/Clength/Ccode",
    ];

    public static function buildPacket($rawData)
    {
        $headCode = ord(substr($rawData, 0, 1));

        if ($headCode === 0xC1) {
            if (ord(substr($rawData, 2, 1)) === 0xF4) {
                if (ord(substr($rawData, 3, 1)) === 0x03) {
                    return new ServerInfo(substr($rawData, 4));
                } elseif (ord(substr($rawData, 3, 1)) === 0x02) {
                    return new ServerList(substr($rawData, 4));
                }
            }
        }

        if ($headCode === 0xC3) {
            $rawData = array_map('ord', str_split($rawData));
            $rawData = mu_decode_c3($rawData);

            Debug::dump($rawData, 'Decoded: ');

            if (ord(substr($rawData, 2, 1)) === 0x01) {
                return new LoginRequest(substr($rawData, 3));
            }
        }

        Debug::dump($rawData, 'Unknown Packet: ');
    }
}