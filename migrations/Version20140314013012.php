<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20140314013012 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $sql = <<<SQL
INSERT INTO `mu_accounts` (`id`, `login`, `password`) VALUES
(1, 'skpd', '$2y$10\$ZjUyYmY1N2M0YmM3NjQzYujagaAc1fyPvFgh16k2ETLWEj77siPbq'),
(2, 'test1', '$2y$10\$MGQyYjA4NDNiZTBjZGY4Yu87eZSvNwrRx/pLaHJJZH1dY8RLcTbBK')
SQL;

        $this->addSql($sql);
    }

    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql", "Migration can only be executed safely on 'mysql'.");

        $this->addSql('DELETE FROM `mu_accounts` WHERE `id` IN (1, 2)');
    }
}
