<?php

namespace Application\Model\Repository;

use Application\Back\Map\Agregator;
use Application\Model\Coordinates;
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
        $agregator = new Agregator($coordinates);;
        $coordinatesModels = $this->findAll();

        foreach ($coordinatesModels as $index => $coordinatesModel) {
            /** @var Coordinates $coordinatesModel */
            if ($agregator->getDistance($coordinatesModel) >= $coordinatesModel->getEmployee()->getAreaAround()->getIntValue()) {
                unset($coordinatesModels[$index]);
            }
        }

        return $coordinatesModels;
    }

}