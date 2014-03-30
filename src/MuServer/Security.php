<?php

namespace MuServer;

class Security
{
//    private $xorKey = [
//         -25, 109,  58, -119, -68, -78, -97, 115,
//          35, -88,  -2,  -74,  73,  93,  57, 93,
//        -118, -53,  99, -115, -22, 125,  43, 95,
//         -61, -79, -23, -125,  41,  81, -24, 86,
//    ];

    public static $loginKey = [ 0xC5, 0xB2, 0x9F, 0x73, 0x23, 0xA8, 0xFE, 0xB6, 0x49, 0x5D ];

    public static function decodeName($name)
    {
        $result = '';
        for ($i = 0; $i < strlen($name); $i++) {
            $result .= chr(ord($name[$i]) ^ self::$loginKey[$i] ^ ord($name[$i - 1]));
        }
		return trim($result);
    }

    public static function encodeName($name)
    {
        for ($i = 0; $i < strlen($name); $i++) {
            $name[$i] = chr(ord($name[$i]) ^ self::$loginKey[$i] ^ ord($name[$i - 1]));
        }
        return trim($name);
    }
}