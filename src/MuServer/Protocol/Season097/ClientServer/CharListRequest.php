<?php

namespace MuServer\Protocol\Season097\ClientServer;

class CharListRequest extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x00;

    public function setData()
    {
        $this->data = '';
    }
}