<?php

namespace MuServer\Protocol\Season097\ServerClient;

class Weather extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0x0F;

    private $weather = 0x22;

    public function setData()
    {
        $this->data = chr($this->weather);
    }

    public static function buildFrom($raw)
    {
        $packet = new self();
        $packet->setWeather(ord($raw));
        return $packet;
    }

    /**
     * @return int
     */
    public function getWeather()
    {
        return $this->weather;
    }

    /**
     * @param int $weather
     */
    public function setWeather($weather)
    {
        $this->weather = $weather;
    }
}