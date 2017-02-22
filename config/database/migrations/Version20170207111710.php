<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170207111710
 * @package Migrations
 */
class Version20170207111710 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql(
            'CREATE TABLE areas (
                id INT AUTO_INCREMENT NOT NULL,
                value VARCHAR (255) DEFAULT NULL,
                int_value INT DEFAULT NULL, PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE contracts (
                id INT AUTO_INCREMENT NOT NULL, 
                name VARCHAR(255) DEFAULT NULL, 
                code VARCHAR(255) DEFAULT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE coordinates (
                id INT AUTO_INCREMENT NOT NULL, 
                employee_id INT DEFAULT NULL, 
                latitude NUMERIC(21, 18) DEFAULT NULL, 
                longitude NUMERIC(21, 18) DEFAULT NULL, 
                INDEX IDX_9816D6768C03F15C (employee_id), 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql(
            'CREATE TABLE weekly_hours (
                id INT AUTO_INCREMENT NOT NULL, 
                value VARCHAR(63) DEFAULT NULL, 
                int_value INT DEFAULT NULL, 
                PRIMARY KEY(id)
            ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB'
        );

        $this->addSql('ALTER TABLE coordinates ADD CONSTRAINT FK_9816D6768C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id)');
        $this->addSql('ALTER TABLE users CHANGE role role VARCHAR(255) DEFAULT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE areas');
        $this->addSql('DROP TABLE contracts');
        $this->addSql('DROP TABLE coordinates');
        $this->addSql('DROP TABLE weekly_hours');
        $this->addSql('ALTER TABLE users CHANGE role role VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
    }
}
