<?php

namespace MuServer\Protocol\Season097\ServerClient;

class InventoryList extends AbstractPacket
{
    /** char */
    private $index = 0;
    /** char[3] */
    private $item = '';

    public function __construct(/*TODO inventory item */)
    {
        $this->setIndex(0);
        $this->setItem(''
            . chr(0xFF) // ??
            . chr(0xFF) // ??
            . chr(0xFF) // ??
        );
        //TODO
    }

    public function setData()
    {
        $this->data  = chr($this->index); // [0]
        $this->data .= str_pad($this->item, 3, chr(0xFF)); // [1] - [3]
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