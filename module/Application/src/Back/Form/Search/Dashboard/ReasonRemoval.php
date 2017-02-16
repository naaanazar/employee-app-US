<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\ReasonRemoval as ReasonRemovalModel;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class ReasonRemoval
 * @package Application\Back\Form\Search\Dashboard
 */
class ReasonRemoval extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        return (new Doctrine(ReasonRemovalModel::class))
            ->setLimit(20, $this->data('page', 1));
    }

}