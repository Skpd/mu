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
        foreach (self::$knownPackets as $packet) {
            if (($data = unpack($packet, $rawData)) !== false) {
                $data['subCode'] = isset($data['subCode']) ? $data['subCode'] : 0;
                switch ($data['class']) {
                    case 0xC1:
                        switch ($data['code']) {
                            case 0xF4:
                                switch ($data['subCode']) {
                                    case 0x03:
                                        return new ServerInfo(substr($rawData, 4));
                                        break;

                                    case 0x02:
                                        return new ServerList(substr($rawData, 4));
                                        break;

                                    default:
                                        break;
                                }
                                break;
                        }
                        break;
                }
            }
        }

        Debug::dump($rawData, 'Unknown Packet: ');
        var_dump($data);
    }
}