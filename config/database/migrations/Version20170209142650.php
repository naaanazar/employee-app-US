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
            ALTER TABLE employees ADD area_id INT DEFAULT NULL,
            ADD contract_id INT DEFAULT NULL,
            ADD weekly_hours_id INT DEFAULT NULL,
            DROP area_around, DROP contract_type,
            DROP weekly_hours_available
        ');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300BD0F409C FOREIGN KEY (area_id) REFERENCES areas (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3002576E0FD FOREIGN KEY (contract_id) REFERENCES contracts (id)');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300B31209DB FOREIGN KEY (weekly_hours_id) REFERENCES weekly_hours (id)');
        $this->addSql('CREATE INDEX IDX_BA82C300BD0F409C ON employees (area_id)');
        $this->addSql('CREATE INDEX IDX_BA82C3002576E0FD ON employees (contract_id)');
        $this->addSql('CREATE INDEX IDX_BA82C300B31209DB ON employees (weekly_hours_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300BD0F409C');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C3002576E0FD');
        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C300B31209DB');
        $this->addSql('DROP INDEX IDX_BA82C300BD0F409C ON employees');
        $this->addSql('DROP INDEX IDX_BA82C3002576E0FD ON employees');
        $this->addSql('DROP INDEX IDX_BA82C300B31209DB ON employees');
        $this->addSql('
            ALTER TABLE employees ADD area_around INT DEFAULT NULL,
            ADD contract_type VARCHAR(511) DEFAULT NULL COLLATE utf8_unicode_ci,
            ADD weekly_hours_available INT DEFAULT NULL,
            DROP area_id, DROP contract_id,
            DROP weekly_hours_id
        ');
    }
}
