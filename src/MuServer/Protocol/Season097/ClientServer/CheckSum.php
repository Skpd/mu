<?php

namespace MuServer\Protocol\Season097\ClientServer;

class CheckSum extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0x03;

    private $key;

    public function setData()
    {
        $this->data = pack('N', $this->key);
    }

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->key = hexdec(bin2hex($rawData));
    }

    /**
     * @return number
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param number $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
}