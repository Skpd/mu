<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Server;

class ServerInfo extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF4;
    protected $subCode = 0x03;

    /** @var Server */
    private $server;

    function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function setData()
    {
        $this->data = str_split(str_pad($this->server->getIp(), 16, chr(0), STR_PAD_RIGHT));
        $this->data = array_map('ord', $this->data);
        $this->data = implode(array_map('chr', $this->data));

        $this->data .= pack('v', $this->server->getPort());
    }

    public static function buildFrom($raw)
    {
        $server = new Server();

        $server->setIp(trim(substr($raw, 0, 15)));
        $server->setPort(current(unpack('v', substr($raw, 16, 2))));

        return new self($server);
    }

    /**
     * @return Server
     */
    public function getServer()
    {
        return $this->server;
    }
}