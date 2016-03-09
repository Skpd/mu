<?php

namespace MuServer\Protocol\Season097\ClientServer;

use MuServer\Entity\Character;
use MuServer\Security;

class CreateCharacter extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x7B;

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

        $this->name = Security::decodeName(substr($rawData, 0, 11));
        $charClass  = ord(substr($this->name, -1, 1));
        $this->name = trim(substr($this->name, 0, -1));

        var_dump($charClass, dechex($charClass));

        switch ($charClass) {
            case 0x19:
                $this->charClass = Character::CLASS_EE;
                break;

            case 0x29:
                $this->charClass = Character::CLASS_DK;
                break;

            case 0x39:
                $this->charClass = Character::CLASS_DW;
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