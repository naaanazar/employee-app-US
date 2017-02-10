<?php

namespace Application\Back\Map;

use Application\Model\Coordinates;

/**
 * Class Agregator
 * @package Application\Back\Map
 */
class Agregator
{

    /**
     * General class defines
     */
    const EARTH_RADIUS            = 6373212;
    const LATITUDE_IN_METERS      = 110574;
    const LONGITUDE_COF_IN_METERS = 111320;

    /**
     * @var Coordinates
     */
    private $coordinates;

    /**
     * Agregator constructor.
     * @param Coordinates $coordinates
     */
    public function __construct(Coordinates $coordinates)
    {
        $this->coordinates = $coordinates;
    }

    /**
     * @param Coordinates $coordinates
     * @return float
     */
    public function getDistance(Coordinates $coordinates)
    {
        $deltaLatitude = $this->toRadian(
            $coordinates->getLatitude() - $this->coordinates->getLatitude()
        );

        $deltaLongitude = $this->toRadian(
            $coordinates->getLongitude() - $this->coordinates->getLongitude()
        );

        $a = sin($deltaLatitude / 2)
            * sin($deltaLatitude / 2)
            + sin($deltaLongitude / 2)
            * sin($deltaLongitude / 2)
            * cos($this->toRadian($this->coordinates->getLatitude()))
            * cos($this->toRadian($coordinates->getLatitude()));

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return static::EARTH_RADIUS * $c;
    }

    /**
     * @param $distance
     * @return float
     */
    public function getMinimumLatitude($distance)
    {
        return $this->coordinates->getLatitude() - ($distance/static::LATITUDE_IN_METERS);
    }

    /**
     * @param $distance
     * @return float
     */
    public function getMaximumLatitude($distance)
    {
        return $this->coordinates->getLatitude() + ($distance/static::LATITUDE_IN_METERS);
    }

    /**
     * @param $distance
     * @return float
     */
    public function getMinimumLongitude($distance)
    {
        return $this->coordinates->getLongitude() - ($distance/$this->getLongitudeDegreeInMeters());
    }

    /**
     * @param $distance
     * @return float
     */
    public function getMaximumLongitude($distance)
    {
        return $this->coordinates->getLongitude() + ($distance/$this->getLongitudeDegreeInMeters());
    }

    /**
     * @return float
     */
    protected function getLongitudeDegreeInMeters()
    {
        return static::LONGITUDE_COF_IN_METERS * cos($this->coordinates->getLatitude());
    }
    
    /**
     * @param $number
     * @return float|int
     */
    protected function toRadian($number)
    {
        return $number * pi() / 180;
    }
}