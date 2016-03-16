<?php

namespace MuServer\Protocol\Season097\ServerClient;

class Message extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x0D;

    private $type = 0x01;
    private $message = '';

    public function __construct($message, $type = 0x01)
    {
        $this->message = $message;
        $this->type = $type;
    }

    public function setData()
    {
        $this->data = '';
        $this->data .= chr($this->type);
        $this->data .= trim($this->message) . chr(0);
    }

    public static function buildFrom($raw)
    {
        return new self(trim(substr($raw, 1)), ord($raw[0]));
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
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