<?php

namespace MuServer\Protocol\Season097\ClientServer;

class LoginRequest extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0xF1;
    protected $subCode = 0x00;

    private $login;
    private $password;
    private $tick;
    private $version;
    private $serial;

    public function setData()
    {
        $this->data = str_pad($this->login, 10, chr(0));
        $this->data .= str_pad($this->password, 10, chr(0));
        $this->data .= pack('N', $this->tick);
        $this->data .= $this->version;
        $this->data .= $this->serial;
    }

    function __construct($rawData)
    {
        $this->data = $rawData;

        $this->login = trim(substr($rawData, 0, 10));
        $this->password = trim(substr($rawData, 10, 10));
        $this->tick = hexdec(bin2hex(substr($rawData, 20, 4)));
        $this->version = substr($rawData, 24, 5);
        $this->serial = substr($rawData, 29);
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $serial
     */
    public function setSerial($serial)
    {
        $this->serial = $serial;
    }

    /**
     * @return string
     */
    public function getSerial()
    {
        return $this->serial;
    }

    /**
     * @param number $tick
     */
    public function setTick($tick)
    {
        $this->tick = $tick;
    }

    /**
     * @return number
     */
    public function getTick()
    {
        return $this->tick;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}