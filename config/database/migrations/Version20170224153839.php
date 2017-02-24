<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170224153839
 * @package Migrations
 */
class Version20170224153839 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP INDEX UNIQ_BA82C30017939202, ADD INDEX IDX_BA82C30017939202 (reason_removal_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {

        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP INDEX IDX_BA82C30017939202, ADD UNIQUE INDEX UNIQ_BA82C30017939202 (reason_removal_id)');
    }
}
