<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;
use MuServer\Entity\Inventory;

class CharList extends AbstractPacket
{
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
    private $charClass = Character::CLASS_DK;

    public function __construct(Character $character)
    {
        $this->setIndex($character->getIndex());
        $this->setCharClass($character->getClass());
        $this->setControlCode($character->getCode());
        $this->setLevel($character->getLevel());
        $this->setName($character->getName());

        $primaryWeapon = Inventory::filter($character->getInventory(), Inventory::POS_PRIMARY);
        $secondaryWeapon = Inventory::filter($character->getInventory(), Inventory::POS_SECONDARY);
        $helm = Inventory::filter($character->getInventory(), Inventory::POS_HELM);
        $armor = Inventory::filter($character->getInventory(), Inventory::POS_ARMOR);
        $pants = Inventory::filter($character->getInventory(), Inventory::POS_PANTS);
        $gloves = Inventory::filter($character->getInventory(), Inventory::POS_GLOVES);
        $boots = Inventory::filter($character->getInventory(), Inventory::POS_BOOTS);
        $wings = Inventory::filter($character->getInventory(), Inventory::POS_WINGS);
        $pet = Inventory::filter($character->getInventory(), Inventory::POS_PET);

        $level = $primaryWeapon === null ? 0 : $primaryWeapon->getShortLevel();
        $level |= ($secondaryWeapon === null ? 0 : $secondaryWeapon->getShortLevel()) << 3;
        $level |= ($helm === null ? 0 : $helm->getShortLevel()) << 6;
        $level |= ($armor === null ? 0 : $armor->getShortLevel()) << 9;
        $level |= ($pants === null ? 0 : $pants->getShortLevel()) << 12;
        $level |= ($gloves === null ? 0 : $gloves->getShortLevel()) << 15;
        $level |= ($boots === null ? 0 : $boots->getShortLevel()) << 18;

        $this->setSet(''
            . ($primaryWeapon === null ? chr(0xFF) : chr($primaryWeapon->getItem()->getType() << 5 | $primaryWeapon->getItem()->getIndex()))   // right arm (weapon)
            . ($secondaryWeapon === null ? chr(0xFF) : chr($secondaryWeapon->getItem()->getType() << 5 | $secondaryWeapon->getItem()->getIndex()))   // left arm (weapon)

            . chr(($helm === null ? 0xF0 : $helm->getItem()->getIndex() << 4) | ($armor === null ? 0x0F : $armor->getItem()->getIndex()))   // helm and armor type
            . chr(($pants === null ? 0xF0 : $pants->getItem()->getIndex() << 4) | ($gloves === null ? 0x0F : $gloves->getItem()->getIndex()))   // gloves and pants type
            . chr(($boots === null ? 0xF0 : $boots->getItem()->getIndex() << 4) | ($wings === null ? 0x0C : $wings->getItem()->getIndex() << 2) | ($pet === null ? 0x03 : $pet->getItem()->getIndex()))   // boots, wings and pet type

            . chr(($level >> 16) & 0xFF)   // level part 1
            . chr(($level >> 8) & 0xFF)   // level part 2
            . chr($level & 0xFF)   // level part 3

            . chr(
                0
                | ($helm === null || $helm->getItem()->getIndex() > 16 ? 0x80 : 0)
                | ($armor === null || $armor->getItem()->getIndex() > 16 ? 0x40 : 0)
                | ($pants === null || $pants->getItem()->getIndex() > 16 ? 0x20 : 0)
                | ($gloves === null || $gloves->getItem()->getIndex() > 16 ? 0x10 : 0)
                | ($boots === null || $boots->getItem()->getIndex() > 16 ? 0x08 : 0)
            ) // item flag

            . chr(
                0
                | ($helm !== null && $helm->isExcl() ? 0x80 : 0)
                | ($armor !== null && $armor->isExcl() ? 0x40 : 0)
                | ($pants !== null && $pants->isExcl() ? 0x20 : 0)
                | ($gloves !== null && $gloves->isExcl() ? 0x10 : 0)
                | ($boots !== null && $boots->isExcl() ? 0x08 : 0)
                | ($primaryWeapon !== null && $primaryWeapon->isExcl() ? 0x04 : 0)
                | ($secondaryWeapon !== null && $secondaryWeapon->isExcl() ? 0x02 : 0)
            ) // excl flag
        );
    }

    public function setData()
    {
        $this->data  = chr($this->index); // [0]
        $this->data .= str_pad($this->name, 10, chr(0)) . chr(0); // null-terminated string [11]
        $this->data .= pack('v1', $this->level); // [13]
        $this->data .= chr($this->controlCode); // [14]
        $this->data .= chr($this->charClass); // [15]
        $this->data .= str_pad($this->set, 10, chr(0xFF)); // [22] - [25]
    }

    public static function buildFrom($raw)
    {
        $character = new Character();
        $character->setIndex(ord($raw[0]));
        $character->setName(trim(substr($raw, 1, 10)));
        $character->setLevel(current(unpack('v', substr($raw, 12, 2))));
        $character->setCode(ord($raw[14]));
        $character->setClass(ord($raw[15]));
//        $character->set(substr($raw, 17));

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