<?php

namespace MuServer;

class Security
{
    private $xorKey = [
         -25, 109,  58, -119, -68, -78, -97, 115,
          35, -88,  -2,  -74,  73,  93,  57, 93,
        -118, -53,  99, -115, -22, 125,  43, 95,
         -61, -79, -23, -125,  41,  81, -24, 86,
    ];

    private $loginKey = [ -4, -49, -85 ];

    public function loadKeys($filename = 'data/Dec1.dat')
    {

    }

    private function hash($dest, $from, $src, $to, $unk /* unsigned char*Dest, int Param10, unsigned char*Src, int Param18, int Param1c*/)
    {
        $length = (($unk + $to - 1) >> 3) - ($to >> 3) + 2;
        $temp = array_slice($src, $to >> 3, $length - 1);
        $EAX = ($unk+$to)&7;
        if ($EAX) {
            $temp[$length-2]&=(0xff)<<(8-$EAX);
        }
        $ESI = $to&7;
	    $EDI = $from&7;
        $temp = $this->shift($temp, $length - 1, -$ESI);
        $temp = $this->shift($temp, $length, $EDI);
        $tempPtr = array_slice($dest, $from >> 3);
        $loopCount = $length - 1 + ($ESI > $EDI ? 1 : 0);
        if ($loopCount) {
            for ($i = 0; $i < $loopCount; $i++) {
                $tempPtr[$i] = $tempPtr[$i] | $temp[$i];
            }
        }
        return $from + $unk;
    }

    private function shift($buff, $length, $shiftLength)
    {
        if ($shiftLength) {
            if ($shiftLength > 0) {
                if ($length - 1 > 0) {
                    for ($i = $length - 1; $i > 0; $i--) {
                        $buff[$i] = ($buff[$i-1]<<(8-$shiftLength))|($buff[$i]>>($shiftLength));
                    }
                }
                $buff[0] = $buff[0]>>$shiftLength;
            } else {
                $shiftLength=-$shiftLength;
                if ($length-1>0) {
                    for ($i=0; $i<$length-1; $i++) {
                        $buff[$i] =($buff[$i+1]>>(8-$shiftLength))|($buff[$i]<<$shiftLength);
                    }
                }

                $buff[$length-1] = $buff[$length-1]<<$shiftLength;
            }
        }

        return $buff;
    }
//    private static $keys = [0xFC, 0xCF, 0xAB];

//    public static function xorName($name)
//    {
//        $result = '';
//        for ($i = 0; $i < strlen($name); $i++) {
//            var_dump(ord($name[$i]));
//            $result .= chr(ord($name[$i]) ^ self::$keys[$i%3]);
//        }
//		return trim($result);
//    }
}