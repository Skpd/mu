<?php

namespace MuServer;

class Server
{
    private $group = 0;
    private $code = 0;
    private $ip = '127.0.0.1';
    private $port = '55901';

    function __construct($group = 0, $code = 0)
    {
        $this->setGroup($group);
        $this->setCode($code);
    }

    /**
     * @return int
     */
    public function getLoad()
    {
        return rand(0, 100);
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param int $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return int
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
    }

    /**
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param string $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

}