<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CheckExe extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x03;

    private $key = 0;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function setData()
    {
        $this->data = chr($this->key);
    }

    public static function buildFrom($raw)
    {
        $packet = new self();
        $packet->setKey($raw);

        return $packet;
    }

    /**
     * @return int
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param int $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }
}