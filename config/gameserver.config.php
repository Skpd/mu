<?php

namespace MuServer;

return [
    'factories' => [
        'GameServer' => function ($sm) {
            $gs = new Game\Server($sm->get('GameLoop'));

            $gs->setServiceLocator($sm);
            $gs->listen(55901, '0.0.0.0');

            return $gs;
        },
    ],
];