<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CharListResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x01;

    private $accountId;
    private $connectionId;

    public function __construct($connectionId, $accountId)
    {
        $this->connectionId = $connectionId;
        $this->accountId = $accountId;
    }

    public function setData()
    {
        $this->data = str_pad($this->accountId, 10, chr(0)) . chr($this->connectionId);
    }

    public static function buildFrom($raw)
    {
        return new self($raw[13], trim(substr($raw, 3, 10)));
    }
}