<?php

namespace MuServer\Protocol\Season097\ClientServer;

class AddPoint extends AbstractPacket
{
    const STRENGTH = 0;
    const AGILITY = 1;
    const VITALITY = 2;
    const ENERGY = 3;

    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x06;

    private $which = 0x00;

    public function setData()
    {
        $this->data = chr($this->which);
    }

    function __construct($rawData)
    {
        $this->data = $rawData;
        $this->which = ord($rawData[0]);
    }

    /**
     * @return mixed
     */
    public function getWhich()
    {
        return $this->which;
    }

    /**
     * @param mixed $which
     */
    public function setWhich($which)
    {
        $this->which = $which;
    }
}