<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170404153015 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees DROP FOREIGN KEY FK_BA82C30041CD9E7A');
        $this->addSql('DROP INDEX IDX_BA82C30041CD9E7A ON employees');
        $this->addSql(
            'ALTER TABLE employees 
              ADD position_applying VARCHAR(1023) DEFAULT NULL,
              ADD location VARCHAR(1023) DEFAULT NULL,
              ADD worked_mlob TINYINT(1) NOT NULL, 
              ADD address_two VARCHAR(1023) DEFAULT NULL, 
              ADD state VARCHAR(255) DEFAULT NULL, 
              ADD work_weekends VARCHAR(255) DEFAULT NULL, 
              ADD customer_service_expierence VARCHAR(255) DEFAULT NULL, 
              ADD business_operations_expierence VARCHAR(255) DEFAULT NULL, 
              ADD management_expierence VARCHAR(255) DEFAULT NULL, 
              ADD expierence_word VARCHAR(255) DEFAULT NULL, 
              ADD expierence_exel VARCHAR(255) DEFAULT NULL, 
              ADD expierence_keypad VARCHAR(255) DEFAULT NULL, 
              ADD delinquent_or_waived TINYINT(1) NOT NULL, 
              ADD criminal_background TINYINT(1) NOT NULL, 
              DROP employer_id'
        );
        $this->addSql('ALTER TABLE employer ADD employee_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE employer ADD CONSTRAINT FK_DE4CF0668C03F15C FOREIGN KEY (employee_id) REFERENCES employees (id)');
        $this->addSql('CREATE INDEX IDX_DE4CF0668C03F15C ON employer (employee_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE employees ADD employer_id INT DEFAULT NULL, DROP position_applying, DROP location, DROP worked_mlob, DROP address_two, DROP state, DROP work_weekends, DROP customer_service_expierence, DROP business_operations_expierence, DROP management_expierence, DROP expierence_word, DROP expierence_exel, DROP expierence_keypad, DROP delinquent_or_waived, DROP criminal_background');
        $this->addSql('ALTER TABLE employees ADD CONSTRAINT FK_BA82C30041CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id)');
        $this->addSql('CREATE INDEX IDX_BA82C30041CD9E7A ON employees (employer_id)');
        $this->addSql('ALTER TABLE employer DROP FOREIGN KEY FK_DE4CF0668C03F15C');
        $this->addSql('DROP INDEX IDX_DE4CF0668C03F15C ON employer');
        $this->addSql('ALTER TABLE employer DROP employee_id');
    }
}
