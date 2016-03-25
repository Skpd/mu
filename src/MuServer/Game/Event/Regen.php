<?php

namespace MuServer\Game\Event;

use MuServer\Entity\Character;
use MuServer\Protocol\Season097\ServerClient\Hp;
use MuServer\Protocol\Season097\ServerClient\Mana;
use React\Socket\ConnectionInterface;

class Regen
{
    private $interval = .5;
    private $callback;

    /** @var \SplObjectStorage|ConnectionInterface[] */
    private $players;

    function __construct(\SplObjectStorage $players)
    {
        $this->players = $players;
    }

    public function tick()
    {
        $this->players->rewind();

        while ($this->players->valid()) {
            /** @var ConnectionInterface $connection */
            $connection = $this->players->current();
            /** @var Character $character */
            $character = $this->players->getInfo();

            if ($character->getLife() < $character->getMaxLife()) {
                $character->setLife($character->getLife() + $character->getMaxLife() / 1000);
                $connection->write(new Hp($character->getLife()));
            }

            if ($character->getMana() < $character->getMaxMana()) {
                $character->setMana($character->getMana() + $character->getMaxMana() / 100);
                $connection->write(new Mana($character->getMana()));
            }

            $this->players->next();
        }
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }

    /**
     * @param int $interval
     */
    public function setInterval($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return mixed
     */
    public function getCallback()
    {
        if ($this->callback === null) {
            $this->callback = [$this, 'tick'];
        }
        return $this->callback;
    }

    /**
     * @param mixed $callback
     */
    public function setCallback($callback)
    {
        $this->callback = $callback;
    }
}