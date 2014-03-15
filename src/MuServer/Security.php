<?php

namespace MuServer;

class Security
{

    private static $keys = [0xFC, 0xCF, 0xAB];

    public static function xorName($name)
    {
        $result = '';
        for ($i = 0; $i < strlen($name); $i++) {
            var_dump(ord($name[$i]));
            $result .= chr(ord($name[$i]) ^ self::$keys[$i%3]);
        }
		return trim($result);
    }
}