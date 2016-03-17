<?php

namespace MuServer\Protocol\Season097\ServerClient;

class Hp extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x26;
    protected $subCode = 0xFF;

    private $hp = 0;

    public function __construct($hp)
    {
        $this->hp = $hp;
    }

    public function setData()
    {
        $this->data = pack('n', $this->hp) . chr(0x00);
    }

    public static function buildFrom($raw)
    {
        return new self(unpack('n', $raw)[1]);
    }

    /**
     * @return int
     */
    public function getHp()
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp($hp)
    {
        $this->hp = $hp;
    }
}