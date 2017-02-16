<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\SourceApplication as SourceApplicationModel;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class Contract
 * @package Application\Back\Form\Search\Dashboard
 */
class SourceApplication extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        return (new Doctrine(SourceApplicationModel::class))
            ->setLimit(20, $this->data('page', 1));
    }

}