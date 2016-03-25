<?php

namespace MuServer\Protocol\Season097\ServerClient;


use MuServer\Entity\Character;

class RenderCharacter extends AbstractPacket
{
    protected $class = 0xC2;
    protected $code = 0x12;
    protected $isDouble = true;

    private $connectionId;
    private $x;
    private $y;
    private $tx;
    private $ty;
    private $direction;
    private $pkStatus;
    private $set;
    private $state;
    private $name;

    public function __construct($connectionId, Character $character)
    {
        $this->connectionId = $connectionId;
        $this->x = 0x8F;
        $this->y = 0x77;
        $this->tx = 1;
        $this->ty = 1;
        $this->direction = 0;
        $this->pkStatus = $character->getPk();
        $this->state = Character::STATE_NORMAL;
        $this->name = $character->getName();
        $this->set = pack('c9', 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00, 0x00);
    }

    function setData()
    {
        $this->data = pack(
            'cncca9ca10ccc',
            1,
            $this->connectionId,
            $this->x,
            $this->y,
            $this->set,
            $this->state,
            $this->name,
            $this->tx,
            $this->ty,
            $this->direction << 4 | $this->pkStatus
        );
    }
}