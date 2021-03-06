<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404115935 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE TABLE employer (
                 id INT AUTO_INCREMENT NOT NULL,
                 name VARCHAR(255) DEFAULT NULL, 
                 city VARCHAR(1023) DEFAULT NULL, 
                 state VARCHAR(1023) DEFAULT NULL, 
                 years_employed VARCHAR(255) DEFAULT NULL, 
                 start DATETIME DEFAULT NULL,
                 end DATETIME DEFAULT NULL,
                 comments LONGTEXT DEFAULT NULL, PRIMARY KEY(id)
             ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD employer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C30041CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('CREATE INDEX IDX_BA82C30041CD9E7A ON employees (employer_id)');

    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C30041CD9E7A');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP INDEX IDX_BA82C30041CD9E7A ON employees');
        $this->addSql('ALTER TABLE employees DROP employer_id');
        $this->addSql('ALTER TABLE search_requests DROP created_at');
    }
}
