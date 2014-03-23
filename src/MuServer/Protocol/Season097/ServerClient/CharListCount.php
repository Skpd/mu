<?php

namespace MuServer\Protocol\Season097\ServerClient;

class CharListCount extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF3;
    protected $subCode = 0;

    /** @var CharList[] */
    private $chars;

    public function __construct(array $chars = [])
    {
        $this->chars = $chars;
    }

    public function setData()
    {
        $this->data = chr(count($this->chars));

        foreach ($this->chars as $char) {
            $this->data .= $char->buildPacket();
        }
    }

    public static function buildFrom($raw)
    {
        $count  = ord($raw[0]);
        $length = 26;
        $chars  = [];

        for ($i = 0; $i < $count; $i++) {
            $char = CharList::buildFrom(substr($raw, $length * $i + 1, $length));
            $chars[] = $char;
        }

        return new self($chars);
    }

    /**
     * @return CharList[]
     */
    public function getChars()
    {
        return $this->chars;
    }

    /**
     * @param CharList[] $chars
     */
    public function setChars($chars)
    {
        $this->chars = $chars;
    }

}