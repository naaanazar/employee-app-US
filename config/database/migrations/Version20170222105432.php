<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170222105432
 * @package Migrations
 */
class Version20170222105432 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql('ALTER TABLE employees ADD image_id INT DEFAULT NULL, DROP image');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3003DA5256D FOREIGN KEY (image_id) REFERENCES images (id)');
        $this->addSql('CREATE INDEX IDX_BA82C3003DA5256D ON employees (image_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C3003DA5256D');
        $this->addSql('DROP INDEX IDX_BA82C3003DA5256D ON employees');
        $this->addSql('ALTER TABLE employees ADD image VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci, DROP image_id');
    }
}
