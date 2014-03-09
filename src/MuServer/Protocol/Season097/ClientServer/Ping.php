<?php

namespace MuServer\Protocol\Season097\ClientServer;

class Ping extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0x0E;

    private $tick;
    private $pAttackSpeed;
    private $mAttackSpeed;
    private $unknown;

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->tick = hexdec(bin2hex(strrev(substr($rawData, 0, 4))));
        $this->pAttackSpeed = hexdec(bin2hex(strrev(substr($rawData, 4, 4))));
        $this->mAttackSpeed = hexdec(bin2hex(strrev(substr($rawData, 8, 4))));
        $this->unknown = hexdec(bin2hex(substr($rawData, 12, 1)));
    }

    /**
     * @param number $mAttackSpeed
     */
    public function setMAttackSpeed($mAttackSpeed)
    {
        $this->mAttackSpeed = $mAttackSpeed;
    }

    /**
     * @return number
     */
    public function getMAttackSpeed()
    {
        return $this->mAttackSpeed;
    }

    /**
     * @param number $pAttackSpeed
     */
    public function setPAttackSpeed($pAttackSpeed)
    {
        $this->pAttackSpeed = $pAttackSpeed;
    }

    /**
     * @return number
     */
    public function getPAttackSpeed()
    {
        return $this->pAttackSpeed;
    }

    /**
     * @param number $tick
     */
    public function setTick($tick)
    {
        $this->tick = $tick;
    }

    /**
     * @return number
     */
    public function getTick()
    {
        return $this->tick;
    }

    /**
     * @param number $unknown
     */
    public function setUnknown($unknown)
    {
        $this->unknown = $unknown;
    }

    /**
     * @return number
     */
    public function getUnknown()
    {
        return $this->unknown;
    }
}