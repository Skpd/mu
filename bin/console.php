<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Symfony\Component\Console\Application;

chdir(dirname(__DIR__));

$sm = include 'src/MuServer/bootstrap.php';

$entityManager = $sm->get('orm_em');

$cli = new Application('MuServer CLI', '0.1');

$cli->setHelperSet(ConsoleRunner::createHelperSet($entityManager));
ConsoleRunner::addCommands($cli);

$cli->addCommands(array(
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
    new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand()
));

$cli->run();