<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20170223092840 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE search_requests ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE search_requests ADD CONSTRAINT FK_6BD27DE4A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_6BD27DE4A76ED395 ON search_requests (user_id)');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE search_requests DROP FOREIGN KEY FK_6BD27DE4A76ED395');
        $this->addSql('DROP INDEX IDX_6BD27DE4A76ED395 ON search_requests');
        $this->addSql('ALTER TABLE search_requests DROP user_id');
    }
}
