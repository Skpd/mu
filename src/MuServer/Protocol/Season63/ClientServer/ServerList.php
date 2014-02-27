<?php

namespace MuServer\Protocol\Season63\ClientServer;

class ServerList extends AbstractPacket
{
    protected $class = 0xC1;
    protected $code = 0xF4;
    protected $subCode = 0x02;
}