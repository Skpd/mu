<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CharList extends AbstractPacket
{
    /** unsigned char */
    private $index = 0;
    /** char[10] */
    private $name = 1;
    /** unsigned short */
    private $level = 2;
    /** unsigned char */
    private $controlCode = 3;
    /** unsigned char[18] */
    private $set = 4;
    /** unsigned char */
    private $guildStatus = 5;

    public function setData()
    {
        $this->data  = chr($this->index);
        $this->data .= str_pad($this->name, 10, chr(0));
        $this->data .= chr(0); // unknown
        $this->data .= pack('v1', $this->level);
        $this->data .= chr($this->controlCode);
        $this->data .= str_pad($this->set, 18, chr(0));
        $this->data .= chr($this->guildStatus);
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
     * @param mixed $guildStatus
     */
    public function setGuildStatus($guildStatus)
    {
        $this->guildStatus = $guildStatus;
    }

    /**
     * @return mixed
     */
    public function getGuildStatus()
    {
        return $this->guildStatus;
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
}