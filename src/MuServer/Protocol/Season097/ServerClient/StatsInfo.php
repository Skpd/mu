<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Character;

class StatsInfo extends AbstractPacket
{
    protected $class = 0xC3;
    protected $code = 0xF3;
    protected $subCode = 0x03;

    private $x = 0x8F;
    private $y = 0x77;
    private $mapNumber = 0;
    private $direction = 0;
    private $exp = 33;
    private $nextLevelAt = 100;
    private $freePoints = 1000;
    private $str = 1;
    private $agi = 1;
    private $vit = 1;
    private $ene = 1;
    private $life = 1;
    private $maxLife = 1000;
    private $mana = 1;
    private $maxMana = 1000;
    private $money = 31337;
    private $pkStatus = 0x03;
    private $ctlCode = 0x00;

    public function __construct(Character $character = null)
    {
        $this->mana = $character->getMana();
        $this->maxMana = $character->getMaxMana();
        $this->life = $character->getLife();
        $this->maxLife = $character->getMaxLife();
        $this->str = $character->getStrength();
        $this->agi = $character->getAgility();
        $this->vit = $character->getVitality();
        $this->ene = $character->getEnergy();
        $this->money = $character->getZen();
        $this->ctlCode = $character->getCode();
        $this->pkStatus = $character->getPk();
        $this->x = $character->getX();
        $this->y = $character->getY();
        $this->map = $character->getMap();
    }

    public function setData()
    {
        $this->data = '';
        $this->data .= chr($this->x);
        $this->data .= chr($this->y);
        $this->data .= chr($this->mapNumber);
        $this->data .= chr($this->direction);
        $this->data .= pack('V', $this->exp);
        $this->data .= pack('V', $this->nextLevelAt);
        $this->data .= pack('v', $this->freePoints);
        $this->data .= pack('v', $this->str);
        $this->data .= pack('v', $this->agi);
        $this->data .= pack('v', $this->vit);
        $this->data .= pack('v', $this->ene);
        $this->data .= pack('v', $this->life);
        $this->data .= pack('v', $this->maxLife);
        $this->data .= pack('v', $this->mana);
        $this->data .= pack('v', $this->maxMana);

        //not sure about these two
        $this->data .= chr(0x00);
        $this->data .= chr(0x00);

        $this->data .= pack('V', $this->money);

        $this->data .= chr($this->pkStatus);
        $this->data .= chr($this->ctlCode);
    }

    /**
     * @param $raw
     * @return StatsInfo
     */
    public static function buildFrom($raw)
    {
        $result = unpack('cx/cy/cmap/cdir/Vexp/Vnextexp/vpoints/v4stats/v4fill/Vmoney/cpk/cctl', $raw);
        $packet = new self();
        $packet->setX($result['x']);
        $packet->setY($result['y']);
        $packet->setMapNumber($result['map']);
        $packet->setDirection($result['dir']);
        $packet->setExp($result['exp']);
        $packet->setNextLevelAt($result['nextexp']);
        $packet->setFreePoints($result['points']);
        $packet->setStr($result['stats1']);
        $packet->setAgi($result['stats2']);
        $packet->setVit($result['stats3']);
        $packet->setEne($result['stats4']);
        $packet->setLife($result['fill1']);
        $packet->setMaxLife($result['fill2']);
        $packet->setMana($result['fill3']);
        $packet->setMaxMana($result['fill4']);
        $packet->setMoney($result['money']);
        $packet->setPkStatus($result['pk']);
        $packet->setCtlCode($result['ctl']);
        return $packet;
    }

    /**
     * @return int
     */
    public function getX()
    {
        return $this->x;
    }

    /**
     * @param int $x
     */
    public function setX($x)
    {
        $this->x = $x;
    }

    /**
     * @return int
     */
    public function getY()
    {
        return $this->y;
    }

    /**
     * @param int $y
     */
    public function setY($y)
    {
        $this->y = $y;
    }

    /**
     * @return int
     */
    public function getMapNumber()
    {
        return $this->mapNumber;
    }

    /**
     * @param int $mapNumber
     */
    public function setMapNumber($mapNumber)
    {
        $this->mapNumber = $mapNumber;
    }

    /**
     * @return int
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param int $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

    /**
     * @return int
     */
    public function getExp()
    {
        return $this->exp;
    }

    /**
     * @param int $exp
     */
    public function setExp($exp)
    {
        $this->exp = $exp;
    }

    /**
     * @return int
     */
    public function getNextLevelAt()
    {
        return $this->nextLevelAt;
    }

    /**
     * @param int $nextLevelAt
     */
    public function setNextLevelAt($nextLevelAt)
    {
        $this->nextLevelAt = $nextLevelAt;
    }

    /**
     * @return int
     */
    public function getFreePoints()
    {
        return $this->freePoints;
    }

    /**
     * @param int $freePoints
     */
    public function setFreePoints($freePoints)
    {
        $this->freePoints = $freePoints;
    }

    /**
     * @return int
     */
    public function getStr()
    {
        return $this->str;
    }

    /**
     * @param int $str
     */
    public function setStr($str)
    {
        $this->str = $str;
    }

    /**
     * @return int
     */
    public function getAgi()
    {
        return $this->agi;
    }

    /**
     * @param int $agi
     */
    public function setAgi($agi)
    {
        $this->agi = $agi;
    }

    /**
     * @return int
     */
    public function getVit()
    {
        return $this->vit;
    }

    /**
     * @param int $vit
     */
    public function setVit($vit)
    {
        $this->vit = $vit;
    }

    /**
     * @return int
     */
    public function getEne()
    {
        return $this->ene;
    }

    /**
     * @param int $ene
     */
    public function setEne($ene)
    {
        $this->ene = $ene;
    }

    /**
     * @return int
     */
    public function getLife()
    {
        return $this->life;
    }

    /**
     * @param int $life
     */
    public function setLife($life)
    {
        $this->life = $life;
    }

    /**
     * @return int
     */
    public function getMaxLife()
    {
        return $this->maxLife;
    }

    /**
     * @param int $maxLife
     */
    public function setMaxLife($maxLife)
    {
        $this->maxLife = $maxLife;
    }

    /**
     * @return int
     */
    public function getMana()
    {
        return $this->mana;
    }

    /**
     * @param int $mana
     */
    public function setMana($mana)
    {
        $this->mana = $mana;
    }

    /**
     * @return int
     */
    public function getMaxMana()
    {
        return $this->maxMana;
    }

    /**
     * @param int $maxMana
     */
    public function setMaxMana($maxMana)
    {
        $this->maxMana = $maxMana;
    }

    /**
     * @return int
     */
    public function getMoney()
    {
        return $this->money;
    }

    /**
     * @param int $money
     */
    public function setMoney($money)
    {
        $this->money = $money;
    }

    /**
     * @return int
     */
    public function getPkStatus()
    {
        return $this->pkStatus;
    }

    /**
     * @param int $pkStatus
     */
    public function setPkStatus($pkStatus)
    {
        $this->pkStatus = $pkStatus;
    }

    /**
     * @return int
     */
    public function getCtlCode()
    {
        return $this->ctlCode;
    }

    /**
     * @param int $ctlCode
     */
    public function setCtlCode($ctlCode)
    {
        $this->ctlCode = $ctlCode;
    }
}