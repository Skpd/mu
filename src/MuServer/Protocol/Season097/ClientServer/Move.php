<?php

namespace MuServer\Protocol\Season097\ClientServer;


class Move extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x10;

    private $x;
    private $y;
    private $path;

    function __construct($rawData)
    {
        $this->data = $rawData;

        $values = unpack('Cx/Cy/x/Cpath', $rawData);

        $this->x = $values['x'];
        $this->y = $values['y'];
        $this->path = $values['path'];
    }

    public function setData()
    {
        $this->data = pack('CCxC', $this->x, $this->y, $this->path);
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
}