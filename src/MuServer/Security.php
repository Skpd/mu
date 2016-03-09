<?php

namespace MuServer;

class Security
{
    public static $loginKey = [ 0xC5, 0xB2, 0x9F, 0x73, 0x23, 0xA8, 0xFE, 0xB6, 0x49, 0x5D ];

    public static function decodeName($name)
    {
        $result = '';

        for ($i = 0; $i < strlen($name); $i++) {
            if ($i > 0) {
                $result .= chr(ord($name[$i]) ^ self::$loginKey[$i] ^ ord($name[$i - 1]));
            } else {
                $result .= chr((ord($name[$i]) ^ self::$loginKey[$i]) - 2);
            }
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