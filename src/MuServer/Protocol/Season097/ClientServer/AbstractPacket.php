<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Protocol\Debug;

abstract class AbstractPacket
{
    protected $class = 0xC1;
    protected $code = null;
    protected $subCode = null;

    protected $data = '';

    abstract public function setData();

    public function buildPacket()
    {
        if (empty($this->data)) {
            $this->setData();
        }

        if ($this->subCode !== null) {
            $packet = pack("cxcca*", $this->class, $this->code, $this->subCode, $this->data);
        } else {
            $packet = pack("cxca*", $this->class, $this->code, $this->data);
        }

        $packet[1] = pack("c", strlen($packet) & 0xFF);

        if ($this->class === 0xC3) {
            $packet = array_map('hexdec', array_map('bin2hex', str_split($this->data)));
            array_unshift($packet, 0, 0, 0);
            $packet[0] = 0xC3;
            $packet[1] = strlen($this->data) + 5;
            $packet[2] = $this->code;
            $packet = mu_encode_c3($packet, $this->code, $this->subCode + 1);
        }

        Debug::dump($packet, 'Sent: ');

        return $packet;
    }

    function __toString()
    {
        return $this->buildPacket();
    }
}