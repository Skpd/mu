<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;

class CharInfoResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x06;

    private $accountId;
    private $name;
    private $index;

    public function __construct($accountId, $name, $index)
    {
        $this->accountId = $accountId;
        $this->name = $name;
        $this->index = $index;
    }

    public function setData()
    {
        $this->data = '';
        $this->data .= str_pad($this->accountId, 10, chr(0));
        $this->data .= str_pad($this->name, 10, chr(0));
        $this->data .= chr($this->index);
    }

    public static function buildFrom($raw)
    {
        return new self(trim(substr($raw, 0, 10)), trim(substr($raw, 10, 10)), ord($raw[20]));
    }
}