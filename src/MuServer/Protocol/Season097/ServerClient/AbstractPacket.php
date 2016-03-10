<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Protocol\Debug;

abstract class AbstractPacket
{
    protected $class = null;
    protected $code = null;
    protected $subCode = null;

    protected $data = [];

    protected $isDouble = false;

    abstract function setData();

    public function buildPacket()
    {
        $this->setData();

        if ($this->class !== null) {
            if ($this->subCode !== null) {
                if ($this->isDouble) {
                    $packet = pack("cxxcca*", $this->class, $this->code, $this->subCode, $this->data);
                } else {
                    $packet = pack("cxcca*", $this->class, $this->code, $this->subCode, $this->data);
                }
            } else {
                if ($this->isDouble) {
                    $packet = pack("cxxca*", $this->class, $this->code, $this->data);
                } else {
                    $packet = pack("cxca*", $this->class, $this->code, $this->data);
                }
            }

            if ($this->isDouble) {
                $packet[1] = pack("c", (strlen($packet) >> 8) & 0xFF);
                $packet[2] = pack("c", strlen($packet) & 0xFF);
            } else {
                $packet[1] = pack("c", strlen($packet) & 0xFF);
            }
        } else {
            $packet = pack("a*", $this->data);
        }

        return $packet;
    }

    function __toString()
    {
        return $this->buildPacket();
    }
}