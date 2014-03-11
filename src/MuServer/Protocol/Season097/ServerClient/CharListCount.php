<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CharListCount extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0;

    /** @var CharList[] */
    private $chars;

    public function __construct(array $chars = [])
    {
        $this->chars = $chars;
    }

    public function setData()
    {
        $this->data = chr(count($this->chars));

        foreach ($this->chars as $char) {
            $this->data .= $char->buildPacket();
        }
    }
}