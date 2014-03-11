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
        $this->data[] = chr(0);
        $this->data[] = chr(0);
        $this->data[] = chr(0);
        $this->data[] = chr(1);

        $this->data = implode($this->data);
    }
}