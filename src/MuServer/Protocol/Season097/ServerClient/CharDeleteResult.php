<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CharDeleteResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x02;

    private $result;

    public function __construct($result)
    {
        $this->result = $result;
    }

    public function setData()
    {
        $this->data = chr($this->result);
    }

    public static function buildFrom($raw)
    {
        throw new \Exception(__CLASS__ . ':' . __FUNCTION__ . ' not implemented');
    }
}