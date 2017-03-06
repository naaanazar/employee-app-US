<?php

namespace Application\Model\Repository;

use Application\Model\Coordinates;
use Application\ORM\Functions\CoordinateDistance;
use Doctrine\ORM\EntityRepository;

/**
 * Class CoordinatesRepository
 * @package Application\Model\Repository
 */
class CoordinatesRepository extends EntityRepository
{

    /**
     * @param Coordinates $coordinates
     * @return array
     */
    public function getCoordinatesInRange(Coordinates $coordinates)
    {
        $this->getEntityManager()
            ->getConfiguration()
            ->addCustomStringFunction('coordinate_distance', CoordinateDistance::class);

        $dql = '
            SELECT coordinate, employee
            FROM Application\Model\Coordinates coordinate
            LEFT JOIN coordinate.employee employee
            LEFT JOIN employee.areaAround area 
            WHERE coordinate_distance(:latitude, :longitude, coordinate.latitude, coordinate.longitude) <= area.intValue
        ';

        $params = [
            'latitude'  => $coordinates->getLatitude(),
            'longitude' => $coordinates->getLongitude()
        ];

        $coordinatesModels = $this->getEntityManager()
            ->createQuery($dql)
            ->execute($params);

        return $coordinatesModels;
    }

    /**
     * @param Coordinates $coordinates
     * @param $area
     * @return array
     */
    public function getCoordinatesInRadius(Coordinates $coordinates, $range)
    {
        $this->getEntityManager()
            ->getConfiguration()
            ->addCustomStringFunction('coordinate_distance', CoordinateDistance::class);

        $dql = '
            SELECT coordinate, employee
            FROM Application\Model\Coordinates coordinate
            LEFT JOIN coordinate.employee employee
            LEFT JOIN employee.areaAround area 
            WHERE coordinate_distance(:latitude, :longitude, coordinate.latitude, coordinate.longitude) <= ' . $range . '
        ';

        $params = [
            'latitude'  => $coordinates->getLatitude(),
            'longitude' => $coordinates->getLongitude()
        ];

        $coordinatesModels = $this->getEntityManager()
            ->createQuery($dql)
            ->execute($params);

        return $coordinatesModels;
    }

}