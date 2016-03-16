<?php

namespace MuServer;

class Security
{
    public static $loginKey = [ 0xC5, 0xB2, 0x9F, 0x73, 0x23, 0xA8, 0xFE, 0xB6, 0x49, 0x5D ];
    public static $c1c2Key = [
        0xE7, 0x6D, 0x3A, 0x89, 0xBC, 0xB2, 0x9F, 0x73,
        0x23, 0xA8, 0xFE, 0xB6, 0x49, 0x5D, 0x39, 0x5D,
        0x8A, 0xCB, 0x63, 0x8D, 0xEA, 0x7D, 0x2B, 0x5F,
        0xC3, 0xB1, 0xE9, 0x83, 0x29, 0x51, 0xE8, 0x56
    ];

    public static function extract(&$raw)
    {
        $end = ord($raw[0]) === 0xC1 ? 2 : 3;
        for ($i = strlen($raw) - 1; $i > $end; $i--) {
            $raw[$i] = chr(ord($raw[$i]) ^ ord($raw[$i - 1]) ^ self::$c1c2Key[$i % 32]);
        }
    }

    public static function pack(&$raw)
    {
        $start = ord($raw[0]) === 0xC1 ? 2 : 3;
        for ($i = $start; $i < strlen($raw); $i++) {
            $raw[$i] = chr(ord($raw[$i]) ^ ord($raw[$i - 1]) ^ self::$c1c2Key[$i % 32]);
        }
    }

    public static function decodeName($name)
    {
        $result = '';

        for ($i = 0; $i < strlen($name); $i++) {
            if ($i > 0) {
                $result .= chr(ord($name[$i]) ^ self::$loginKey[$i] ^ ord($name[$i - 1]));
            } else {
                $result .= chr((ord($name[$i]) ^ self::$loginKey[$i]));
            }
        }

		return trim($result);
    }

    public static function encodeName($name)
    {
        for ($i = 0; $i < strlen($name); $i++) {
            if ($i == 0) {
                $name[$i] = chr(ord($name[$i]) ^ self::$loginKey[$i]);
            } else {
                $name[$i] = chr(ord($name[$i]) ^ self::$loginKey[$i] ^ ord($name[$i - 1]));
            }
        }

        return trim($name);
    }
}