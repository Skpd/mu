<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Protocol\Debug;

abstract class AbstractPacket
{
    protected $class = 0xC1;
    protected $code = null;
    protected $subCode = null;

    protected $data = '';

    public function buildPacket()
    {
        if ($this->subCode !== null) {
            $packet = pack("cxcca*", $this->class, $this->code, $this->subCode, $this->data);
        } else {
            $packet = pack("cxca*", $this->class, $this->code, $this->data);
        }

        $packet[1] = pack("c", strlen($packet) & 0xFF);

        Debug::dump($packet, 'Sent: ');

        return $packet;
    }

    function __toString()
    {
        return $this->buildPacket();
    }
}