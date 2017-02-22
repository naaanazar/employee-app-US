<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170202163831
 * @package Migrations
 */
class Version20170202163831 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('
            CREATE TABLE employees (
                id INT AUTO_INCREMENT NOT NULL,
                user_id INT DEFAULT NULL,
                address_line VARCHAR(1023) DEFAULT NULL,
                full_name VARCHAR(511) DEFAULT NULL,
                mobile_phone VARCHAR(215) DEFAULT NULL,
                landline_phone VARCHAR(215) DEFAULT NULL,
                email VARCHAR(511) DEFAULT NULL,
                experience TINYINT(1) NOT NULL,
                area_around INT DEFAULT NULL,
                driving_licence TINYINT(1) NOT NULL,
                car_available TINYINT(1) NOT NULL,
                contract_type VARCHAR(511) DEFAULT NULL,
                weekly_hours_available INT DEFAULT NULL,
                startDate DATETIME DEFAULT NULL,
                comments LONGTEXT DEFAULT NULL,
                hourly_rate NUMERIC(5, 2) DEFAULT NULL, INDEX IDX_BA82C300A76ED395 (user_id),
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C300A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE employees');
    }
}
