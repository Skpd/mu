<?php

namespace MuServer\Protocol\Season097\ClientServer;


class Chat extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x00;

    private $name;
    private $message;

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->name = trim(substr($rawData, 0, 10));
        $this->message = trim(substr($rawData, 10));
    }

    public function setData()
    {
        $this->data = str_pad($this->name, 10, chr(0)) . $this->message . chr(0);
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
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}