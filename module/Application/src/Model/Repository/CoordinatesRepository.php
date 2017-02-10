<?php

namespace Application\Model\Repository;

use Application\Back\Map\Agregator;
use Application\Model\Coordinates;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

/**
 * Class CoordinatesRepository
 * @package Application\Model\Repository
 */
class CoordinatesRepository extends EntityRepository
{

    /**
     * @param Coordinates $coordinates
     * @param $range
     * @return array
     */
    public function getCoordinatesInRange(Coordinates $coordinates, $range)
    {
        $agregator = new Agregator($coordinates);

        $criteria = new Criteria();
        $criteria
            ->where($criteria->expr()->gt('longitude', $agregator->getMinimumLongitude($range)))
            ->andWhere($criteria->expr()->lt('longitude', $agregator->getMaximumLongitude($range)))
            ->andWhere($criteria->expr()->gt('latitude', $agregator->getMinimumLatitude($range)))
            ->andWhere($criteria->expr()->lt('latitude', $agregator->getMaximumLatitude($range)));

        $coordinatesModels = $this->matching($criteria)->toArray();

        foreach ($coordinatesModels as &$coordinatesModel) {

            /** @var Coordinates $coordinatesModel */
            $maximumRange = max(
                $agregator->getDistance($coordinatesModel) > $range,
                $coordinatesModel->getEmployee()->getAreaAround()->getIntValue()
            );

            if ($maximumRange > $range) {
                unset($coordinatesModel);
            }
        }

        return $coordinatesModels;
    }

}