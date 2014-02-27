<?php

// http://forum.ragezone.com/f196/calculate-version-651948/

function h2a($str)
{
    $x = '';
    for ($i=0; $i < strlen($str); $i=$i+2)
    {
        $x .= chr(hexdec(substr($str, $i, 2)));
    }
    return $x;
}


$mver = '0.97.00'; // server version


$mver_split = addcslashes("$mver",'0..9.');
$n = explode("\\", $mver_split);
$base = 30; // hex value (48 dec)
$h = dechex(hexdec($base)+hexdec($n[1])+hexdec(1));
$h .= dechex(hexdec($base)+hexdec($n[3])+hexdec(2));
$h .= dechex(hexdec($base)+hexdec($n[4])+hexdec(3));
$h .= dechex(hexdec($base)+hexdec($n[6])+hexdec(4));
$h .= dechex(hexdec($base)+hexdec($n[7])+hexdec(5));
$mumv = h2a($h);


echo "Hex: [".$h."] - Ascii: [".$mumv."]";