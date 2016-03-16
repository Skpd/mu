<?php

namespace MuServer\Protocol\Season097\ClientServer;

class DeleteCharacter extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x02;

    private $name;
    private $pass;

    public function __construct($rawData)
    {
        $this->data = $rawData;
        $this->name = trim(substr($rawData, 0, 10));
        $this->pass = trim(substr($rawData, 10, 20));
    }

    public function setData()
    {
        $this->data = str_pad($this->name, 10, chr(0));
        $this->data .= str_pad($this->pass, 10, chr(0));
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
    public function getPass()
    {
        return $this->pass;
    }

    /**
     * @param mixed $pass
     */
    public function setPass($pass)
    {
        $this->pass = $pass;
    }
}