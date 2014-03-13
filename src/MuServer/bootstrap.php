<?php

namespace MuServer;

chdir(dirname(dirname(__DIR__)));

require 'vendor/autoload.php';

use Zend\Config\Config;
use Zend\ServiceManager\ServiceManager;

$config = new Config([]);

$di = new \DirectoryIterator('config');

foreach ($di as $file) {
    /** @var \SplFileInfo $file */

    if (!$file->isDir() && substr($file->getFilename(), -11) == '.config.php') {
        $merge = new Config(include $file->getRealPath());
        $config->merge($merge);
    }
}

$config = $config->toArray();

return new ServiceManager(new \Zend\ServiceManager\Config($config));