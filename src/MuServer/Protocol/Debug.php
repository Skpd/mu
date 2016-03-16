<?php

namespace MuServer\Protocol;

class Debug
{
    public static function dump($data, $label = null, $return = false)
    {
        $str = '';
        for ($i = 0; $i < strlen($data); $i++) {
            $str .= sprintf('0x%02X, ', ord($data[$i]));
        }

        if ($return) {
            return $str;
        }

        echo $label . ' ' . $str . PHP_EOL;

        return null;
    }
}