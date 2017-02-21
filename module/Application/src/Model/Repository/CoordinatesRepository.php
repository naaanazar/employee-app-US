<?php

namespace Application\Model\Repository;

use Application\Back\Map\Agregator;
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
        $agregator = new Agregator($coordinates);
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

        foreach ([] as $index => $coordinatesModel) {
            /** @var Coordinates $coordinatesModel */
            if ($agregator->getDistance($coordinatesModel) >= $coordinatesModel->getEmployee()->getAreaAround()->getIntValue()) {
                unset($coordinatesModels[$index]);
            }
        }

        return $coordinatesModels;
    }

}