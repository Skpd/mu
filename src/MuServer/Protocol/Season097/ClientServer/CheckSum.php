<?php

namespace MuServer\Protocol\Season097\ClientServer;

class CheckSum extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0x03;

    private $key;

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->key = hexdec(bin2hex($rawData));
    }
}