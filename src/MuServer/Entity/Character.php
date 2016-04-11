<?php

namespace MuServer\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Character
{
    const CLASS_DW = 0;
    const CLASS_DK = 32;
    const CLASS_EE = 64;

    const CLASS_MG = 96;

    const CLASS_SM = 128;
    const CLASS_BK = 160;
    const CLASS_ME = 192;

    const STATE_POISONED = 1;
    const STATE_FROZEN = 2;
    const STATE_ATTACK = 4;
    const STATE_DEFENCE = 8;
    const STATE_NORMAL = 0;

    private $id;
    private $index;
    private $name;
    private $level;
    private $code;
    private $class;
    private $life;
    private $maxLife;
    private $mana;
    private $maxMana;
    private $strength;
    private $agility;
    private $vitality;
    private $energy;
    private $zen;
    private $pk;
    private $map = 0;
    private $x = 0x8F;
    private $y = 0x77;
    private $inventory;

    private $account;

    /**
     * @return int
     */
    public function getMap()
    {
        return $this->map;
    }

    /**
     * @param int $map
     */
    public function setMap($map)
    {
        $this->map = $map;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return mixed
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * @param mixed $pk
     */
    public function setPk($pk)
    {
        $this->pk = $pk;
    }

    /**
     * @return mixed
     */
    public function getZen()
    {
        return $this->zen;
    }

    /**
     * @param mixed $zen
     */
    public function setZen($zen)
    {
        $this->zen = $zen;
    }

    /**
     * @return mixed
     */
    public function getStrength()
    {
        return $this->strength;
    }

    /**
     * @param mixed $strength
     */
    public function setStrength($strength)
    {
        $this->strength = $strength;
    }

    /**
     * @return mixed
     */
    public function getAgility()
    {
        return $this->agility;
    }

    /**
     * @param mixed $agility
     */
    public function setAgility($agility)
    {
        $this->agility = $agility;
    }

    /**
     * @return mixed
     */
    public function getVitality()
    {
        return $this->vitality;
    }

    /**
     * @param mixed $vitality
     */
    public function setVitality($vitality)
    {
        $this->vitality = $vitality;
    }

    /**
     * @return mixed
     */
    public function getEnergy()
    {
        return $this->energy;
    }

    /**
     * @param mixed $energy
     */
    public function setEnergy($energy)
    {
        $this->energy = $energy;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     */
    public function setAccount($account)
    {
        $this->account = $account;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * @param mixed $class
     */
    public function setClass($class)
    {
        $this->class = $class;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return Inventory[]|ArrayCollection
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param mixed $inventory
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }

    /**
     * @return mixed
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param mixed $level
     */
    public function setLevel($level)
    {
        $this->level = $level;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getLife()
    {
        return $this->life;
    }

    /**
     * @param mixed $life
     */
    public function setLife($life)
    {
        $this->life = $life;
    }

    /**
     * @return mixed
     */
    public function getMaxLife()
    {
        return $this->maxLife;
    }

    /**
     * @param mixed $maxLife
     */
    public function setMaxLife($maxLife)
    {
        $this->maxLife = $maxLife;
    }

    /**
     * @return mixed
     */
    public function getMana()
    {
        return $this->mana;
    }

    /**
     * @param mixed $mana
     */
    public function setMana($mana)
    {
        $this->mana = $mana;
    }

    /**
     * @return mixed
     */
    public function getMaxMana()
    {
        return $this->maxMana;
    }

    /**
     * @param mixed $maxMana
     */
    public function setMaxMana($maxMana)
    {
        $this->maxMana = $maxMana;
    }
}