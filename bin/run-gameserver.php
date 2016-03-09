<?php

chdir(dirname(__DIR__));

mu_decoder_init("data/Enc2.dat", "data/Dec1.dat");

$sm = include 'src/MuServer/bootstrap.php';

/** @var \MuServer\Game\Server $gs */
$gs = $sm->get('GameServer');
$gs->init();

/** @var \React\EventLoop\LoopInterface $loop */
$loop = $sm->get('GameLoop');

$loop->run();

