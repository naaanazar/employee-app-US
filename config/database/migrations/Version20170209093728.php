<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209093728 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'ALTER TABLE employees 
            ADD city VARCHAR(1023) DEFAULT NULL, 
            ADD zip INT DEFAULT NULL, 
            ADD surname VARCHAR(511) DEFAULT NULL, 
            CHANGE address_line address VARCHAR(1023) DEFAULT NULL, 
            CHANGE full_name name VARCHAR(511) DEFAULT NULL'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'ALTER TABLE employees 
            ADD address_line VARCHAR(1023) DEFAULT NULL COLLATE utf8_unicode_ci,
            ADD full_name VARCHAR(511) DEFAULT NULL COLLATE utf8_unicode_ci, 
            DROP address, 
            DROP city, 
            DROP zip, 
            DROP name, 
            DROP surname'
        );
    }
}
