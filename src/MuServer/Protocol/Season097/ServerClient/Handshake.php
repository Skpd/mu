<?php

namespace MuServer\Protocol\Season097\ServerClient;

class Handshake extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 00;

    public function setData()
    {
        $this->data = chr(1);
    }

    public static function buildFrom($raw)
    {
        return new self();
    }
}