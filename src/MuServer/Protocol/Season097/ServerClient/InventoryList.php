<?php

namespace MuServer\Protocol\Season097\ServerClient;

use MuServer\Entity\Inventory;

class InventoryList extends AbstractPacket
{
    /** char */
    private $index = 0;
    /** char[4] */
    private $item = '';

    public function __construct(Inventory $inventoryItem = null)
    {
        $this->setIndex($inventoryItem->getPosition());
        $this->setItem(pack('c*',
            $inventoryItem->getItem()->getType() << 5 | $inventoryItem->getItem()->getIndex(),
            $inventoryItem->getLevel() << 3 | ($inventoryItem->getLuck() ? 4 : 0) | $inventoryItem->getAdd(),
            $inventoryItem->getDurability(),
            0
            | ($inventoryItem->getExcellent1() ? 1 << 0 : 0)
            | ($inventoryItem->getExcellent2() ? 1 << 1 : 0)
            | ($inventoryItem->getExcellent3() ? 1 << 2 : 0)
            | ($inventoryItem->getExcellent4() ? 1 << 3 : 0)
            | ($inventoryItem->getExcellent5() ? 1 << 4 : 0)
            | ($inventoryItem->getExcellent6() ? 1 << 5 : 0)
            | ($inventoryItem->getExcellent7() ? 1 << 6 : 0)
        ));
    }

    public function setData()
    {
        $this->data  = chr($this->index);
        $this->data .= str_pad($this->item, 4, chr(0x00));
    }

    public static function buildFrom($raw)
    {
        //TODO

        return new self();
    }

    /**
     * @return mixed
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @param mixed $index
     */
    public function setIndex($index)
    {
        $this->index = $index;
    }

    /**
     * @return mixed
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param mixed $item
     */
    public function setItem($item)
    {
        $this->item = $item;
    }
}