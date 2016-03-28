<?php

namespace MuServer\Entity;

/**
 * Inventory
 */
class Inventory
{
    /**
     * @var integer
     */
    private $position;

    /**
     * @var integer
     */
    private $durability;

    /**
     * @var integer
     */
    private $level;

    /**
     * @var integer
     */
    private $luck;

    /**
     * @var integer
     */
    private $add;

    /**
     * @var integer
     */
    private $skill;

    /**
     * @return int
     */
    public function getSkill()
    {
        return $this->skill;
    }

    /**
     * @param int $skill
     */
    public function setSkill($skill)
    {
        $this->skill = $skill;
    }

    /**
     * @var boolean
     */
    private $excellent1;

    /**
     * @var boolean
     */
    private $excellent2;

    /**
     * @var boolean
     */
    private $excellent3;

    /**
     * @var boolean
     */
    private $excellent4;

    /**
     * @var boolean
     */
    private $excellent5;

    /**
     * @var boolean
     */
    private $excellent6;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \MuServer\Entity\Item
     */
    private $item;

    /**
     * @var \MuServer\Entity\Character
     */
    private $character;

    /**
     * @return Character
     */
    public function getCharacter()
    {
        return $this->character;
    }

    /**
     * @param Character $character
     */
    public function setCharacter($character)
    {
        $this->character = $character;
    }

    /**
     * Set position
     *
     * @param integer $position
     *
     * @return Inventory
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set durability
     *
     * @param integer $durability
     *
     * @return Inventory
     */
    public function setDurability($durability)
    {
        $this->durability = $durability;

        return $this;
    }

    /**
     * Get durability
     *
     * @return integer
     */
    public function getDurability()
    {
        return $this->durability;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Inventory
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return integer
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set luck
     *
     * @param integer $luck
     *
     * @return Inventory
     */
    public function setLuck($luck)
    {
        $this->luck = $luck;

        return $this;
    }

    /**
     * Get luck
     *
     * @return integer
     */
    public function getLuck()
    {
        return $this->luck;
    }

    /**
     * Set add
     *
     * @param integer $add
     *
     * @return Inventory
     */
    public function setAdd($add)
    {
        $this->add = $add;

        return $this;
    }

    /**
     * Get add
     *
     * @return integer
     */
    public function getAdd()
    {
        return $this->add;
    }

    /**
     * Set excellent1
     *
     * @param boolean $excellent1
     *
     * @return Inventory
     */
    public function setExcellent1($excellent1)
    {
        $this->excellent1 = $excellent1;

        return $this;
    }

    /**
     * Get excellent1
     *
     * @return boolean
     */
    public function getExcellent1()
    {
        return $this->excellent1;
    }

    /**
     * Set excellent2
     *
     * @param boolean $excellent2
     *
     * @return Inventory
     */
    public function setExcellent2($excellent2)
    {
        $this->excellent2 = $excellent2;

        return $this;
    }

    /**
     * Get excellent2
     *
     * @return boolean
     */
    public function getExcellent2()
    {
        return $this->excellent2;
    }

    /**
     * Set excellent3
     *
     * @param boolean $excellent3
     *
     * @return Inventory
     */
    public function setExcellent3($excellent3)
    {
        $this->excellent3 = $excellent3;

        return $this;
    }

    /**
     * Get excellent3
     *
     * @return boolean
     */
    public function getExcellent3()
    {
        return $this->excellent3;
    }

    /**
     * Set excellent4
     *
     * @param boolean $excellent4
     *
     * @return Inventory
     */
    public function setExcellent4($excellent4)
    {
        $this->excellent4 = $excellent4;

        return $this;
    }

    /**
     * Get excellent4
     *
     * @return boolean
     */
    public function getExcellent4()
    {
        return $this->excellent4;
    }

    /**
     * Set excellent5
     *
     * @param boolean $excellent5
     *
     * @return Inventory
     */
    public function setExcellent5($excellent5)
    {
        $this->excellent5 = $excellent5;

        return $this;
    }

    /**
     * Get excellent5
     *
     * @return boolean
     */
    public function getExcellent5()
    {
        return $this->excellent5;
    }

    /**
     * Set excellent6
     *
     * @param boolean $excellent6
     *
     * @return Inventory
     */
    public function setExcellent6($excellent6)
    {
        $this->excellent6 = $excellent6;

        return $this;
    }

    /**
     * Get excellent6
     *
     * @return boolean
     */
    public function getExcellent6()
    {
        return $this->excellent6;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set item
     *
     * @param \MuServer\Entity\Item $item
     *
     * @return Inventory
     */
    public function setItem(\MuServer\Entity\Item $item = null)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return \MuServer\Entity\Item
     */
    public function getItem()
    {
        return $this->item;
    }
}

