<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;

class JoinPosition extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x03;

    private $numberH;
    private $numberL;
    private $x;
    private $y;
    private $mapNumber;
    private $direction;

    public function __construct(Character $character, $connectionId)
    {
        $this->numberH = $connectionId >> 8;
        $this->numberL = $connectionId & 0xff;
        $this->x = 0;
        $this->y = 0;
        $this->mapNumber = 0;
        $this->direction = 0;
    }

    public function setData()
    {
        $this->data = chr($this->numberH);
        $this->data .= chr($this->numberL);
        $this->data .= chr($this->x);
        $this->data .= chr($this->y);
        $this->data .= chr($this->mapNumber);
        $this->data .= chr($this->direction);
    }
}