<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170209142650 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            ALTER TABLE employees
            ADD contract_id INT DEFAULT NULL,
            DROP contract_type
        ');

        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3002576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
        $this->addSql('CREATE INDEX IDX_BA82C3002576E0FD ON employees (contract_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C3002576E0FD');
        $this->addSql('DROP INDEX IDX_BA82C3002576E0FD ON employees');
        $this->addSql('
            ALTER TABLE employees
            ADD contract_type VARCHAR(511) DEFAULT NULL COLLATE utf8_unicode_ci,
            DROP contract_id
        ');
    }
}
