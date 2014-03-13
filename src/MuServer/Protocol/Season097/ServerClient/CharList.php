<?php

namespace MuServer\Protocol\Season097\ServerClient;

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

    public function setData()
    {
        $this->data  = chr($this->index); // [0]
        $this->data .= str_pad($this->name, 10, chr(0)) . chr(0); // null-terminated string [11]
        $this->data .= pack('v1', $this->level); // [13]
        $this->data .= chr($this->controlCode); // [14]
        $this->data .= chr($this->charClass); // [15]
        $this->data .= str_pad($this->set, 10, chr(-1)); // [22] - [25]
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