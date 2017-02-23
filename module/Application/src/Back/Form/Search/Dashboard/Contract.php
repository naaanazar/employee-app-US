<?php

namespace Application\Back\Form\Search\Dashboard;

use Application\Back\Form\Search\AbstractSearch;
use Application\Back\Paginator\Doctrine;
use Application\Model\Contract as ContractModel;
use Application\Module;
use Zend\Paginator\Paginator;

/**
 * Class Contract
 * @package Application\Back\Form\Search\Dashboard
 */
class Contract extends AbstractSearch
{

    /**
     * @return Paginator
     */
    public function getResult()
    {
        return (new Doctrine(ContractModel::class))
            ->setLimit(20, $this->data('page', 1));
    }

}