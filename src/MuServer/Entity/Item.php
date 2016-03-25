<?php

namespace MuServer\Entity;

/**
 * Item
 */
class Item
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var integer
     */
    private $type;

    /**
     * @var integer
     */
    private $index;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $twoHanded;

    /**
     * @var integer
     */
    private $dropLevel;

    /**
     * @var integer
     */
    private $sizeX;

    /**
     * @var integer
     */
    private $sizeY;

    /**
     * @var integer
     */
    private $damageMin;

    /**
     * @var integer
     */
    private $damageMax;

    /**
     * @var integer
     */
    private $defenceRate;

    /**
     * @var integer
     */
    private $defence;

    /**
     * @var integer
     */
    private $attackSpeed;

    /**
     * @var integer
     */
    private $walkingSpeed;

    /**
     * @var integer
     */
    private $durability;

    /**
     * @var integer
     */
    private $raise;

    /**
     * @var integer
     */
    private $requireStrength;

    /**
     * @var integer
     */
    private $requireAgility;

    /**
     * @var integer
     */
    private $requireEnergy;

    /**
     * @var integer
     */
    private $requireLevel;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var boolean
     */
    private $dwAllowed;

    /**
     * @var boolean
     */
    private $dkAllowed;

    /**
     * @var boolean
     */
    private $elfAllowed;

    /**
     * @var boolean
     */
    private $mgAllowed;

    /**
     * @var boolean
     */
    private $iceAttribute;

    /**
     * @var boolean
     */
    private $poisonAttribute;

    /**
     * @var boolean
     */
    private $lightningAttribute;

    /**
     * @var boolean
     */
    private $fireAttribute;


    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Item
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set index
     *
     * @param integer $index
     *
     * @return Item
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get index
     *
     * @return integer
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set twoHanded
     *
     * @param  $twoHanded
     *
     * @return Item
     */
    public function setTwoHanded($twoHanded)
    {
        $this->twoHanded = $twoHanded;

        return $this;
    }

    /**
     * Get twoHanded
     * @return int
     */
    public function getTwoHanded()
    {
        return $this->twoHanded;
    }

    /**
     * Set dropLevel
     *
     * @param integer $dropLevel
     *
     * @return Item
     */
    public function setDropLevel($dropLevel)
    {
        $this->dropLevel = $dropLevel;

        return $this;
    }

    /**
     * Get dropLevel
     *
     * @return integer
     */
    public function getDropLevel()
    {
        return $this->dropLevel;
    }

    /**
     * Set sizeX
     *
     * @param integer $sizeX
     *
     * @return Item
     */
    public function setSizeX($sizeX)
    {
        $this->sizeX = $sizeX;

        return $this;
    }

    /**
     * Get sizeX
     *
     * @return integer
     */
    public function getSizeX()
    {
        return $this->sizeX;
    }

    /**
     * Set sizeY
     *
     * @param integer $sizeY
     *
     * @return Item
     */
    public function setSizeY($sizeY)
    {
        $this->sizeY = $sizeY;

        return $this;
    }

    /**
     * Get sizeY
     *
     * @return integer
     */
    public function getSizeY()
    {
        return $this->sizeY;
    }

    /**
     * Set damageMin
     *
     * @param integer $damageMin
     *
     * @return Item
     */
    public function setDamageMin($damageMin)
    {
        $this->damageMin = $damageMin;

        return $this;
    }

    /**
     * Get damageMin
     *
     * @return integer
     */
    public function getDamageMin()
    {
        return $this->damageMin;
    }

    /**
     * Set damageMax
     *
     * @param integer $damageMax
     *
     * @return Item
     */
    public function setDamageMax($damageMax)
    {
        $this->damageMax = $damageMax;

        return $this;
    }

    /**
     * Get damageMax
     *
     * @return integer
     */
    public function getDamageMax()
    {
        return $this->damageMax;
    }

    /**
     * Set defenceRate
     *
     * @param integer $defenceRate
     *
     * @return Item
     */
    public function setDefenceRate($defenceRate)
    {
        $this->defenceRate = $defenceRate;

        return $this;
    }

    /**
     * Get defenceRate
     *
     * @return integer
     */
    public function getDefenceRate()
    {
        return $this->defenceRate;
    }

    /**
     * Set defence
     *
     * @param integer $defence
     *
     * @return Item
     */
    public function setDefence($defence)
    {
        $this->defence = $defence;

        return $this;
    }

    /**
     * Get defence
     *
     * @return integer
     */
    public function getDefence()
    {
        return $this->defence;
    }

    /**
     * Set attackSpeed
     *
     * @param integer $attackSpeed
     *
     * @return Item
     */
    public function setAttackSpeed($attackSpeed)
    {
        $this->attackSpeed = $attackSpeed;

        return $this;
    }

    /**
     * Get attackSpeed
     *
     * @return integer
     */
    public function getAttackSpeed()
    {
        return $this->attackSpeed;
    }

    /**
     * Set walkingSpeed
     *
     * @param integer $walkingSpeed
     *
     * @return Item
     */
    public function setWalkingSpeed($walkingSpeed)
    {
        $this->walkingSpeed = $walkingSpeed;

        return $this;
    }

    /**
     * Get walkingSpeed
     *
     * @return integer
     */
    public function getWalkingSpeed()
    {
        return $this->walkingSpeed;
    }

    /**
     * Set durability
     *
     * @param integer $durability
     *
     * @return Item
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
     * Set raise
     *
     * @param integer $raise
     *
     * @return Item
     */
    public function setRaise($raise)
    {
        $this->raise = $raise;

        return $this;
    }

    /**
     * Get raise
     *
     * @return integer
     */
    public function getRaise()
    {
        return $this->raise;
    }

    /**
     * Set requireStrength
     *
     * @param integer $requireStrength
     *
     * @return Item
     */
    public function setRequireStrength($requireStrength)
    {
        $this->requireStrength = $requireStrength;

        return $this;
    }

    /**
     * Get requireStrength
     *
     * @return integer
     */
    public function getRequireStrength()
    {
        return $this->requireStrength;
    }

    /**
     * Set requireAgility
     *
     * @param integer $requireAgility
     *
     * @return Item
     */
    public function setRequireAgility($requireAgility)
    {
        $this->requireAgility = $requireAgility;

        return $this;
    }

    /**
     * Get requireAgility
     *
     * @return integer
     */
    public function getRequireAgility()
    {
        return $this->requireAgility;
    }

    /**
     * Set requireEnergy
     *
     * @param integer $requireEnergy
     *
     * @return Item
     */
    public function setRequireEnergy($requireEnergy)
    {
        $this->requireEnergy = $requireEnergy;

        return $this;
    }

    /**
     * Get requireEnergy
     *
     * @return integer
     */
    public function getRequireEnergy()
    {
        return $this->requireEnergy;
    }

    /**
     * Set requireLevel
     *
     * @param integer $requireLevel
     *
     * @return Item
     */
    public function setRequireLevel($requireLevel)
    {
        $this->requireLevel = $requireLevel;

        return $this;
    }

    /**
     * Get requireLevel
     *
     * @return integer
     */
    public function getRequireLevel()
    {
        return $this->requireLevel;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Item
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set dwAllowed
     *
     * @param  $dwAllowed
     *
     * @return Item
     */
    public function setDwAllowed( $dwAllowed)
    {
        $this->dwAllowed = $dwAllowed;

        return $this;
    }

    /**
     * Get dwAllowed
     *
     * @return 
     */
    public function getDwAllowed()
    {
        return $this->dwAllowed;
    }

    /**
     * Set dkAllowed
     *
     * @param  $dkAllowed
     *
     * @return Item
     */
    public function setDkAllowed( $dkAllowed)
    {
        $this->dkAllowed = $dkAllowed;

        return $this;
    }

    /**
     * Get dkAllowed
     *
     * @return 
     */
    public function getDkAllowed()
    {
        return $this->dkAllowed;
    }

    /**
     * Set elfAllowed
     *
     * @param  $elfAllowed
     *
     * @return Item
     */
    public function setElfAllowed( $elfAllowed)
    {
        $this->elfAllowed = $elfAllowed;

        return $this;
    }

    /**
     * Get elfAllowed
     *
     * @return 
     */
    public function getElfAllowed()
    {
        return $this->elfAllowed;
    }

    /**
     * Set mgAllowed
     *
     * @param  $mgAllowed
     *
     * @return Item
     */
    public function setMgAllowed( $mgAllowed)
    {
        $this->mgAllowed = $mgAllowed;

        return $this;
    }

    /**
     * Get mgAllowed
     *
     * @return 
     */
    public function getMgAllowed()
    {
        return $this->mgAllowed;
    }

    /**
     * Set iceAttribute
     *
     * @param  $iceAttribute
     *
     * @return Item
     */
    public function setIceAttribute( $iceAttribute)
    {
        $this->iceAttribute = $iceAttribute;

        return $this;
    }

    /**
     * Get iceAttribute
     *
     * @return 
     */
    public function getIceAttribute()
    {
        return $this->iceAttribute;
    }

    /**
     * Set poisonAttribute
     *
     * @param  $poisonAttribute
     *
     * @return Item
     */
    public function setPoisonAttribute( $poisonAttribute)
    {
        $this->poisonAttribute = $poisonAttribute;

        return $this;
    }

    /**
     * Get poisonAttribute
     *
     * @return 
     */
    public function getPoisonAttribute()
    {
        return $this->poisonAttribute;
    }

    /**
     * Set lightningAttribute
     *
     * @param  $lightningAttribute
     *
     * @return Item
     */
    public function setLightningAttribute( $lightningAttribute)
    {
        $this->lightningAttribute = $lightningAttribute;

        return $this;
    }

    /**
     * Get lightningAttribute
     *
     * @return 
     */
    public function getLightningAttribute()
    {
        return $this->lightningAttribute;
    }

    /**
     * Set fireAttribute
     *
     * @param  $fireAttribute
     *
     * @return Item
     */
    public function setFireAttribute( $fireAttribute)
    {
        $this->fireAttribute = $fireAttribute;

        return $this;
    }

    /**
     * Get fireAttribute
     *
     * @return 
     */
    public function getFireAttribute()
    {
        return $this->fireAttribute;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }
}

