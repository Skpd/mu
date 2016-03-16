<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CheckSumResult extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0x03;

    private $key;

    public function __construct($key = 0)
    {
        $this->key = $key;
    }

    public function setData()
    {
        $this->data = pack('v', $this->key);
    }
}