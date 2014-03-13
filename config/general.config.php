<?php

namespace MuServer;

use React\EventLoop\Factory;

return [
    'factories' => [
        'GameLoop' => function () {
            $loop = Factory::create();

            return $loop;
        },
        'ConnectLoop' => function () {
            $loop = Factory::create();

            return $loop;
        },
    ],
];