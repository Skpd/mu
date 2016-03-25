<?php

namespace MuServer\Protocol\Season097\ServerClient;


class Move extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x10;

    private $x;
    private $y;
    private $path;
    private $connectionId;

    function __construct($connectionId, $x, $y, $path)
    {
        $this->connectionId = $connectionId;
        $this->x = $x;
        $this->y = $y;
        $this->path = $path;
    }

    public function setData()
    {
        $this->data = pack('nCCxC', $this->connectionId, $this->x, $this->y, $this->path);
    }

    /**
     * @return mixed
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param mixed $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return mixed
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param mixed $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return mixed
     */
    public function getConnectionId()
    {
        return $this->connectionId;
    }

    /**
     * @param mixed $connectionId
     */
    public function setConnectionId($connectionId)
    {
        $this->connectionId = $connectionId;
    }

}