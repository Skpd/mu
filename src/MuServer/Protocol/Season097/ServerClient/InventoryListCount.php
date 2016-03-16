<?php

namespace MuServer\Protocol\Season097\ServerClient;

class InventoryListCount extends AbstractPacket
{
    protected $class = 0xC2;
    protected $code = 0xF3;
    protected $subCode = 0x10;

    protected $isDouble = true;

    /** @var InventoryList[] */
    private $inventory = [];

    public function __construct(array $inventory = [])
    {
        $this->inventory = $inventory;
    }

    public function setData()
    {
        $this->data = chr(count($this->inventory));

        foreach ($this->inventory as $item) {
            $this->data .= $item->buildPacket();
        }
    }

    public static function buildFrom($raw)
    {
        $count  = ord($raw[0]);
        $length = 5;
        $inventory  = [];

        for ($i = 0; $i < $count; $i++) {
            $item = CharList::buildFrom(substr($raw, $length * $i + 1, $length));
            $inventory[] = $item;
        }

        return new self($inventory);
    }

    /**
     * @return InventoryList[]
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param InventoryList[] $inventory
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }
}