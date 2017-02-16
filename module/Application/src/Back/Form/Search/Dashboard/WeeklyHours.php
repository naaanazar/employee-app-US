<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\WeeklyHours as WeeklyHoursModel;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class WeeklyHours
 * @package Application\Back\Form\Search\Dashboard
 */
class WeeklyHours extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        $criteria = [];
        return (new Doctrine(WeeklyHoursModel::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}