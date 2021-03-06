<?php

namespace MuServer\Protocol\Season63\ClientServer;

class ServerInfo extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF4;
    protected $subCode = 0x03;

    private $serverCode;

    function __construct($rawData)
    {
        $this->serverCode = ord($rawData[0]);
        $this->data = $rawData;
    }

    public function getServerCode()
    {
        return $this->serverCode % 20;
    }

    public function getServerGroup()
    {
        return floor($this->serverCode / 20);
    }
}