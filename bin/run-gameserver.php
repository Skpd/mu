<?php

chdir(dirname(__DIR__));

$sm = include 'src/MuServer/bootstrap.php';

/** @var \MuServer\Game\Server $gs */
$gs = $sm->get('GameServer');
$gs->init();

/** @var \React\EventLoop\LoopInterface $loop */
$loop = $sm->get('GameLoop');

$loop->run();

