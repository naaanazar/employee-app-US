<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170223161623
 * @package Migrations
 */
class Version20170223161623 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE files (
            id INT AUTO_INCREMENT NOT NULL, 
            path VARCHAR(255) NOT NULL, 
            name VARCHAR(255) NOT NULL, 
            mime VARCHAR(255) NOT NULL, 
            size DOUBLE PRECISION NOT NULL, 
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE files');
    }
}
