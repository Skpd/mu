<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;

class CharCreateResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x01;

    private $result;
    private $char;
    private $name;

    public function __construct($result, Character $char = null, $name = null)
    {
        $this->result = $result;
        $this->char = $char;
        $this->name = $name;
    }

    public function setData()
    {
        $this->data = '';
        $this->data .= chr($this->result);

        if ($this->result) {
            $this->data .= str_pad($this->char->getName(), 10, chr(0));
            $this->data .= chr($this->char->getIndex());
                        //TODO: change to inventory items
            $this->data .= pack('c*', 0xFF, 0xFF, 0xFF, 0xFF, 0xFF, 0x00, 0x00, 0x00, 0xF8, 0x00);
        } else {
            $this->data .= str_pad($this->name, 10, chr(0));
            $this->data .= chr(-1);
        }
    }

    public static function buildFrom($raw)
    {
        throw new \Exception(__CLASS__ . ':' . __FUNCTION__ . ' not implemented');
    }
}