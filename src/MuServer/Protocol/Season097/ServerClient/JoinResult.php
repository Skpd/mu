<?php

namespace MuServer\Protocol\Season097\ServerClient;

class JoinResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF1;
    protected $subCode = 0;

    private $isOk;
    private $version;

    public function __construct($isOk = true, $version = '0.95.04')
    {
        $this->isOk = $isOk;
        $this->version = str_split(preg_replace('/[^0-9]/', '', $version));
    }

    public function setData()
    {
        $this->data[0] = chr($this->isOk ? 1 : 0); // result

        $this->data[1] = chr(0); // unknown
        $this->data[2] = chr(0); // join attempts?

        foreach ($this->version as $char) {
            $this->data[] = chr(ord(intval($char)));
        }

        $this->data = implode($this->data);
    }

    public static function buildFrom($raw)
    {
        return new self(ord($raw[0]) == 1, substr($raw, 3));
    }
}