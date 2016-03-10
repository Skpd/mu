<?php

namespace MuServer\Protocol\Season097\ClientServer;

class ClientClose extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF1;
    protected $subCode = 0x03;

    private $flag = 0x00;

    public function setData()
    {
        $this->data = chr($this->flag);
    }

    function __construct($rawData)
    {
        $this->data = $rawData;
        $this->flag = ord($rawData[0]);
    }

    /**
     * @return mixed
     */
    public function getFlag()
    {
        return $this->flag;
    }

    /**
     * @param mixed $flag
     */
    public function setFlag($flag)
    {
        $this->flag = $flag;
    }
}