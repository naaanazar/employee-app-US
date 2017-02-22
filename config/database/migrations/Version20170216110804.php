<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170216110804 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE reason_removal (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) DEFAULT NULL,
                code VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('
            CREATE TABLE source_application (
                id INT AUTO_INCREMENT NOT NULL,
                name VARCHAR(255) DEFAULT NULL,
                code VARCHAR(255) DEFAULT NULL,
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'

        );
        $this->addSql('ALTER TABLE employees ADD source_application_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C3009D7A6668 FOREIGN KEY (source_application_id) REFERENCES source_application (id)');
        $this->addSql('CREATE INDEX IDX_BA82C3009D7A6668 ON employees (source_application_id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C3009D7A6668');
        $this->addSql('DROP TABLE reason_removal');
        $this->addSql('DROP TABLE source_application');
        $this->addSql('DROP INDEX IDX_BA82C3009D7A6668 ON employees');
        $this->addSql('ALTER TABLE employees DROP source_application_id');
    }
}
