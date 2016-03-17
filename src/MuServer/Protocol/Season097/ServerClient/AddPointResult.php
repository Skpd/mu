<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Protocol\Season097\ClientServer\AddPoint;

class AddPointResult extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0x06;

    private $success = 0;
    private $which = 0;
    private $maxLifeOrMana = 0;

    public function __construct($success, $which, $maxLifeOrMana)
    {
        $this->success = $success;
        $this->which = $which;
        $this->maxLifeOrMana = $maxLifeOrMana;
    }

    public function setData()
    {
        $this->data = chr(($this->success << 4) | $this->which);
        $this->data .= chr(0); //unknown
        switch ($this->which) {
            case AddPoint::VITALITY:
            case AddPoint::ENERGY:
                $this->data .= pack('v', $this->maxLifeOrMana);
                break;
        }
    }

    public static function buildFrom($raw)
    {
        throw new \Exception(__CLASS__ . ':' . __FUNCTION__ . ' not implemented');
    }

    /**
     * @return int
     */
    public function getMaxLifeOrMana()
    {
        return $this->maxLifeOrMana;
    }

    /**
     * @param int $maxLifeOrMana
     */
    public function setMaxLifeOrMana($maxLifeOrMana)
    {
        $this->maxLifeOrMana = $maxLifeOrMana;
    }

    /**
     * @return int
     */
    public function getWhich()
    {
        return $this->which;
    }

    /**
     * @param int $which
     */
    public function setWhich($which)
    {
        $this->which = $which;
    }

    /**
     * @return int
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * @param int $success
     */
    public function setSuccess($success)
    {
        $this->success = $success;
    }
}