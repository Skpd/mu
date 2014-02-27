<?php

namespace MuServer\Protocol;

use MuServer\Protocol\ServerClient\AbstractPacket;

class Debug
{
    public static function dump($data, $label = null, $return = false)
    {
//        $data = $packet->buildPacket();

        $str = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $str .= sprintf('%02X', ord($data[$i]));
        }
        if ($return) {
            return $str;
        }
        echo $label . ' ' . $str . PHP_EOL;
    }
}