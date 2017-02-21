<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Class Version20170217153355
 * @package Migrations
 */
class Version20170221140722 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
    }

    public function postUp(Schema $schema)
    {
        parent::postUp($schema);
        $this->connection->executeQuery(
            'CREATE FUNCTION to_radian(val decimal)
            RETURNS DECIMAL(50, 30) DETERMINISTIC
            RETURN PI() * val / 180;'
        );
        $this->connection->executeQuery(
            'CREATE FUNCTION coordinate_distance(lat decimal, lng decimal, lat1 decimal, lng1 decimal)
            RETURNS decimal(50, 30) DETERMINISTIC
            READS SQL DATA
            BEGIN
            SET @deltalat:=lat1-lat;
            SET @deltalng:=lng1-lng;
            SET @a := SIN(@deltalat/2)
            * SIN(@deltalat/2)
            + SIN(@deltalng/2)
            * SIN(@deltalng/2)
            * COS(to_radian(lat))
            * COS(to_radian(lat1));
            
            SET @c := 2 * atan2(sqrt(@a), sqrt(1 - @a));
            
            RETURN 6373212.0 * @c;
            END;'
        );
    }
}
