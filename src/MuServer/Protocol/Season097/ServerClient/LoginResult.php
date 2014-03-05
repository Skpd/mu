<?php

namespace MuServer\Protocol\Season097\ServerClient;

class LoginResult extends AbstractPacket
{
    const RESULT_BAD_PASSWORD = 0x00;
    const RESULT_SUCCESS = 0x01;
    const RESULT_BAD_ID = 0x03;
    const RESULT_IN_USE = 0x04;
    const RESULT_ACCOUNT_BANNED = 0x05;
    const RESULT_FIXED_EXPIRATION = 0x0C;
    const RESULT_DETERMINATION_EXPIRATION = 0x0D;
    const RESULT_BLOCKED = 0x0E;
    const RESULT_BAD_COUNTRY = 0xD2;

    protected $class = 0xC3;
    protected $code = 0x01;

    private $result;

    public function __construct($result = self::RESULT_BAD_ID)
    {
        $this->result = $result;
        $this->isDouble = false;
    }

    public function setData()
    {
        $this->data = range(0, 15, 1);

        $this->data[0] = chr($this->result); // result

//        $this->data[1] = chr(0); // unknown number ?
//        $this->data[2] = chr(0); // user number?
//        $this->data[2] = chr(0); // user db number?

        $this->data = implode($this->data);
    }
}