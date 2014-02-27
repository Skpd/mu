<?php

namespace MuServer\Protocol\Season63\ServerClient;

class JoinResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF1;
    protected $subCode = 0;

    private $isOk = true;

    public function __construct($isOk = true)
    {
        $this->isOk = $isOk;
        $this->isDouble = false;
    }

    public function setData()
    {
        $this->data[0] = chr($this->isOk ? 1 : 0); // result

        $this->data[1] = chr(0); // unknown
        $this->data[2] = chr(0); // join attempts?

        // version:
        // human readable - 0.97.00
        // server version (strip dots) - 09700
        // client version - 1;:45
        $this->data[3] = chr(0x30);
        $this->data[4] = chr(0x39);
        $this->data[5] = chr(0x37);
        $this->data[6] = chr(0x30);
        $this->data[7] = chr(0x30);

        $this->data = implode($this->data);
    }
}