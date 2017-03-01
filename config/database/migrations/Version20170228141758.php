<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170228141758
 * @package Migrations
 */
class Version20170228141758 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');
    }

    /**
     * @param Schema $schema
     */
    public function postUp(Schema $schema)
    {
        $this->connection->executeQuery(
            'DROP FUNCTION IF EXISTS coordinate_distance;
            CREATE FUNCTION coordinate_distance(lat double(30, 24), lng double(30, 24), lat1 double(30, 24), lng1 double(30, 24)) RETURNS double(30,24)
                READS SQL DATA
                DETERMINISTIC
            BEGIN
                SET @deltalat:=to_radian(lat1-lat);
                SET @deltalng:=to_radian(lng1-lng);
                SET @startlat:=to_radian(lat);
                SET @endlat:=to_radian(lat1);
                SET @a := SIN(@deltalat/2) 
                * SIN(@deltalat/2) 
                + SIN(@deltalng/2)
                * SIN(@deltalng/2) 
                * COS(@startlat) 
                * COS(@endlat);
                SET @res:=(2*atan2(sqrt(@a),sqrt(1-@a)) * 6373212.0);
                RETURN @res;
            END'
        );

        $this->connection->executeQuery(
            'DROP FUNCTION IF EXISTS to_radian;
            CREATE FUNCTION to_radian(val double(30, 24)) RETURNS double(30,24)
                DETERMINISTIC
            RETURN 3.1415926535898 * val / 180'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP function `coordinate_distance`');
        $this->addSql('DROP function `coordinate_distance`');
    }
}
