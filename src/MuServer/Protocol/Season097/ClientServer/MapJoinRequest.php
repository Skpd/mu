<?php

namespace MuServer\Protocol\Season097\ClientServer;

class MapJoinRequest extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x79;

    private $name;

    public function setData()
    {
        $this->data = str_pad($this->name, 10, chr(0));
    }

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->name = trim(substr($rawData, 0, 10));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}