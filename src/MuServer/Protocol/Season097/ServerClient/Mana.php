<?php

namespace MuServer\Protocol\Season097\ServerClient;

class Mana extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x27;
    protected $subCode = 0xFF;

    private $mana = 0;

    public function __construct($mana)
    {
        $this->mana = $mana;
    }

    public function setData()
    {
        $this->data = pack('n', $this->mana) . chr(0x00);
    }

    public static function buildFrom($raw)
    {
        return new self(unpack('n', $raw)[1]);
    }

    /**
     * @return int
     */
    public function getMana()
    {
        return $this->mana;
    }

    /**
     * @param int $mana
     */
    public function setMana($mana)
    {
        $this->mana = $mana;
    }
}