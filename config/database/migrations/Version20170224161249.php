<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170224161249
 * @package Migrations
 */
class Version20170224161249 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees ADD job_status VARCHAR(215) NOT NULL, DROP deleted');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees ADD deleted TINYINT(1) NOT NULL, DROP job_status');
    }
}
