<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170222104604
 * @package Migrations
 */
class Version20170222104604 extends AbstractMigration
{

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('CREATE TABLE images (
            id INT AUTO_INCREMENT NOT NULL,
            original VARCHAR(255) NOT NULL,
            thumbnail VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );
    }

    public function postUp(Schema $schema)
    {
        $this->connection->executeQuery(
            'INSERT INTO images (original, thumbnail) VALUES (\'img/user-profile.png\', \'img/user-profile-thumb.png\')'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE images');
    }

}
