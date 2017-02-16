<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\ListWhyDelete;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class WhyDelete
 * @package Application\Back\Form\Search\Dashboard
 */
class WhyDelete extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        $criteria = [];
        return (new Doctrine(ListWhyDelete::class, $criteria))
            ->setLimit(20, $this->data('page', 1));
    }

}