<?php

namespace Application\Model\Repository;

use Application\Model\Image;
use Doctrine\ORM\EntityRepository;

/**
 * Class ImageRepository
 * @package Application\Model\Repository
 */
class ImageRepository extends EntityRepository
{

    /**
     * @param array $criteria
     * @param array|null $orderBy
     * @return Image
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        $result = parent::findOneBy($criteria, $orderBy);

        if (null === $result) {
            $result = $this->findOneBy(
                [
                    'original' => Image::DEFAULT_IMAGE
                ]
            );
        }

        return $result;
    }

}