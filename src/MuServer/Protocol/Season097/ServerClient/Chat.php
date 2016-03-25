<?php

namespace MuServer\Protocol\Season097\ServerClient;


class Chat extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x00;

    private $name;
    private $message;

    function __construct($name, $message)
    {
        $this->name = $name;
        $this->message = $message;
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