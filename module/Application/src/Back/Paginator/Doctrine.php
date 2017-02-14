<?php

namespace Application\Back\Paginator;

use Zend\Paginator\Paginator;

class Doctrine extends Paginator
{
    /**
     * Doctrine constructor.
     * @param \Zend\Paginator\Adapter\AdapterInterface|\Zend\Paginator\AdapterAggregateInterface $entity
     * @param array $criteria
     * @param null $orderBy
     */
    public function __construct($entity, $criteria = [], $orderBy = null)
    {
        parent::__construct(new \Application\Back\Paginator\Adapter\Doctrine($entity, $criteria = [], $orderBy = null));
    }

    /**
     * @param int $limit
     * @param int $page
     * @return $this
     */
    public function setLimit($limit = 20, $page = 1)
    {
        $this->setItemCountPerPage($limit);
        $this->setCurrentPageNumber($page);

        return $this;
    }


}