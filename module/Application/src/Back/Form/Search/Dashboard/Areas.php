<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\Area;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class Areas
 * @package Application\Back\Form\Search\Dashboard
 */
class Areas extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        $criteria = [];
        return (new Doctrine(Area::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}