<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Protocol\Season097\ClientServer\ServerInfo;
use MuServer\Server;

class ServerList extends AbstractPacket
{
    protected $class = 0xC2;
    protected $code = 0xF4;
    protected $subCode = 0x02;
    protected $isDouble = true;

    /** @var Server[]  */
    private $servers = [];
    
    public function setData()
    {
        $this->data = [];

//        $this->data[0] = 0;
        $this->data[0] = 0;

        foreach ($this->servers as $n => $server) {
            $this->data[0]++;

            $this->data[$n * 4 + 1] = $server->getGroup() * 20 + $server->getCode();
            $this->data[$n * 4 + 2] = 0;

            $this->data[$n * 4 + 3] = $server->getLoad();
            $this->data[$n * 4 + 4] = 0;
        }

        $this->data = implode(array_map('chr', $this->data));
    }

    /**
     * @param Server[] $servers
     */
    public function setServers($servers)
    {
        $this->servers = $servers;
    }

    public function addServer(Server $server)
    {
        $this->servers[] = $server;
    }
    
    /**
     * @return Server[]
     */
    public function getServers()
    {
        return $this->servers;
    }

    public function findServer(ServerInfo $info)
    {
        foreach ($this->servers as $server) {
            if ($server->getGroup() == $info->getServerGroup() && $server->getCode() == $info->getServerCode()) {
                return $server;
            }
        }

        throw new \RuntimeException("Server  not found.");
    }
}