<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Entity\Character;
use MuServer\Protocol\Debug;
use MuServer\Security;

class CreateCharacter extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x01;

    private $name;
    private $charClass;

    public function setData()
    {
        $this->data = Security::encodeName(str_pad($this->name, 10, chr(0)));
        $this->data .= chr($this->charClass);
    }

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->name = trim(substr($rawData, 0, 10));
        $charClass  = ord($rawData[10]);

        switch ($charClass) {
            case 0x00:
                $this->charClass = Character::CLASS_DW;
                break;

            case 0x10:
                $this->charClass = Character::CLASS_DK;
                break;

            case 0x20:
                $this->charClass = Character::CLASS_EE;
                break;

            case 0x30:
                $this->charClass = Character::CLASS_MG;
                break;

            default:
                throw new \UnexpectedValueException("Unexpected class $charClass.");
                break;
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
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