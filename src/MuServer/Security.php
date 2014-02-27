<?php

namespace MuServer;

class Security
{
    private static $xorKey = [0x3F08A79B, 0xE25CC287, 0x93D27AB9, 0x20DEA7BF];
    private static $c3Keys = [0x9B,0xA7,0x08,0x3F,0x87,0xC2,0x5C,0xE2, 0xB9,0x7A,0xD2,0x93,0xBF,0xA7,0xDE,0x20];
    private static $c2Keys = [0xE7,0x6D,0x3A,0x89,0xBC,0xB2,0x9F,0x73, 0x23,0xA8,0xFE,0xB6,0x49,0x5D,0x39,0x5D, 0x8A,0xCB,0x63,0x8D,0xEA,0x7D,0x2B,0x5F, 0xC3,0xB1,0xE9,0x83,0x29,0x51,0xE8,0x56];
    private static $loginKeys = [0xFC, 0xCF, 0xAB];
    public static $decKey;
    public static $encKey;

    public static function loadKeys($fileName, &$keys)
    {
        $file = new \SplFileObject($fileName);

        $file->fseek(6);
        for ($i = 0; $i < 4; $i++) {
            $keys = self::$c3Keys[$i] ^ $file->fgetc();
        }

        $file->fseek(16);
        for ($i = 0; $i < 4; $i++) {
            $keys = self::$c3Keys[$i] ^ $file->fgetc();
        }

        $file->fseek(16);
        for ($i = 0; $i < 4; $i++) {
            $keys = self::$c3Keys[$i] ^ $file->fgetc();
        }
    }

    public static function decrypt($source)
    {
        $decoded = 0;

        if (strlen($source) > 0) {
            do {

            } while (strlen($decoded) < strlen($source));
        }
    }

    public static function encrypt($source)
    {

    }
}