<?php

namespace MuServer\Protocol\Season097\ServerClient;

class LoginResult extends AbstractPacket
{
    const BAD_PASSWORD = 0x00;
    const SUCCESS = 0x01;
    const IN_USE = 0x03;
    const SERVER_IS_FULL = 0x04;
    const ACCOUNT_BANNED = 0x05;
    const NEW_VERSION_REQUIRED = 0x06;
    const CONNECTION_ERROR = 0x07;
    const CLOSED_BY_ATTEMPTS = 0x08;
    const BO_CHARGE_INFO = 0x09;
    const SUBSCRIPTION_IS_OVER = 0x0A;
    const SUBSCRIPTION_IS_OVER2 = 0x0B;
    const SUBSCRIPTION_IS_OVER_IP = 0x0C;
    const INVALID_ACCOUNT = 0x0D;
    const CONNECTION_ERROR2 = 0x0E;

    protected $class = 0xC1;
    protected $code = 0xF1;
    protected $subCode = 0x01;

    private $result;

    public function __construct($result = self::NEW_VERSION_REQUIRED)
    {
        $this->result = $result;
    }

    public function setData()
    {
        $this->data = chr($this->result); // result
    }

    /**
     * @return int
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * @param int $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }
}