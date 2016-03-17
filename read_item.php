<?php

require_once 'vendor/autoload.php';

$keys = [0xFC, 0xCF, 0xAB];

$filename = $argv[1];

$contents = file_get_contents($filename);
$step = 56;

$items = [];

for ($i = 0; $i < strlen($contents); $i += $step) {
    $item = substr($contents, $i, $step);

    if (strlen($item) < $step) {
        continue;
    }

    for ($k = 0; $k < $step; $k++) {
        $item[$k] = chr(ord($item[$k]) ^ $keys[$k % 3]);
    }

    $name = trim(substr($item, 0, 30));
    $item = substr($item, 30);

    $item = unpack(
        'CtwoHanded' .
        '/CdropLvl' .
        '/CsizeX' .
        '/CsizeY' .
        '/CdmgMin' .
        '/CdmgMax' .
        '/Crate' .
        '/Cdef' .
        '/Cxxx' .
        '/CattackSpeed' .
        '/CwalkSpeed' .
        '/Cdurability' .
        '/Craise' .
        '/Cstr' .
        '/Cdex' .
        '/Cene' .
        '/Clevel' .
        '/Cvalue' .
        '/Cdw' .
        '/Cdk' .
        '/Celf' .
        '/Cmg' .
        '/Cice' .
        '/Cpoison' .
        '/Clightning' .
        '/Cfire',
        $item
    );

//    echo ((int) ($i / $step / 32)) . ' ' . ($i / $step) . ' ' . $name . PHP_EOL;

    $item['group'] = (int) ($i / $step / 32);
    $item['id']    = $i / $step % 32;
    $item['name']  = $name;

    if (!empty($name)) {
        $items[] = $item;
    }
}

var_dump($items);