<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;

class CharList extends AbstractPacket
{
    const CLASS_DW = 0;
    const CLASS_DK = 32;
    const CLASS_EE = 64;

    const CLASS_MG = 96;

    const CLASS_SM = 128;
    const CLASS_BK = 160;
    const CLASS_ME = 192;

    /** char */
    private $index = 0;
    /** char[10] */
    private $name = '';
    /** unsigned short le */
    private $level = 0;
    /** char */
    private $controlCode = 0;
    /** char[10] */
    private $set = '';
    /** char */
    private $charClass = self::CLASS_BK;

    public function __construct(Character $character)
    {
        $this->setIndex($character->getIndex());
        $this->setCharClass($character->getClass());
        $this->setControlCode($character->getCode());
        $this->setLevel($character->getLevel());
        $this->setName($character->getName());
        $this->setSet(
            chr(0xFF)   // right arm (weapon)
            . chr(0xFF)   // left arm (weapon / shield)

            . chr(0xFF)   // helm and armor type
            . chr(0xFF)   // gloves and pants type
            . chr(0xFF)   // boots and wings type

            . chr(0x00)   // boots and gloves level
            . chr(0x00)   // pants armor helm gloves level
            . chr(0x00)   // helm level

            . chr(0xF8)   // 2nd wings ?

            . chr(0xFE)   // is exc ? 1 << 1 | 1 << 2 ... 1 << 7
        );
    }

    public function setData()
    {
        $this->data  = chr($this->index); // [0]
        $this->data .= str_pad($this->name, 10, chr(0)) . chr(0); // null-terminated string [11]
        $this->data .= pack('v1', $this->level); // [13]
        $this->data .= chr($this->controlCode); // [14]
        $this->data .= chr($this->charClass); // [15]
        $this->data .= str_pad($this->set, 10, chr(-1)); // [22] - [25]
    }

    public static function buildFrom($raw)
    {
        $character = new Character();
        $character->setIndex(ord($raw[0]));
        $character->setName(trim(substr($raw, 1, 10)));
        $character->setLevel(current(unpack('v', substr($raw, 13, 2))));
        $character->setCode(ord($raw[15]));
        $character->setCode(ord($raw[16]));
        $character->setCode(substr($raw, 17));

        return new self($character);
    }

    /**
     * @param mixed $controlCode
     */
    public function setControlCode($controlCode)
    {
        $this->controlCode = $controlCode;
    }

    /**
     * @return mixed
     */
    public function getControlCode()
    {
        return $this->controlCode;
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
    public function getIndex()
    {
        return $this->index;
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
    public function getLevel()
    {
        return $this->level;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $set
     */
    public function setSet($set)
    {
        $this->set = $set;
    }

    /**
     * @return mixed
     */
    public function getSet()
    {
        return $this->set;
    }

    /**
     * @param mixed $charClass
     */
    public function setCharClass($charClass)
    {
        $this->charClass = $charClass;
    }

    /**
     * @return mixed
     */
    public function getCharClass()
    {
        return $this->charClass;
    }
}