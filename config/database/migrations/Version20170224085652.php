<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170224085652 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees ADD reason_removal_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C30017939202 FOREIGN KEY (reason_removal_id) REFERENCES reason_removal (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA82C30017939202 ON employees (reason_removal_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C30017939202');
        $this->addSql('DROP INDEX UNIQ_BA82C30017939202 ON employees');
        $this->addSql('ALTER TABLE employees DROP reason_removal_id');
    }
}
