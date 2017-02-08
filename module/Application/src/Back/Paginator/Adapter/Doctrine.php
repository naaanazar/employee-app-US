<?php

namespace Application\Back\Paginator\Adapter;

use Application\Module;

use Zend\Paginator\Adapter\AdapterInterface;

/**
 * Class Doctrine
 * @package Application\Back\Paginator\Adapter
 */
class Doctrine implements AdapterInterface
{

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @var array
     */
    protected $orderBy;

    /**
     * Doctrine constructor.
     * @param $entity
     * @param array $criteria
     * @param null $orderBy
     */
    public function __construct($entity, $criteria = [], $orderBy = null)
    {
        $this->entity = $entity;
        $this->criteria = $criteria;
        $this->orderBy = $orderBy;
    }

    /**
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $repo = Module::entityManager()->getRepository($this->entity);
        return $repo->findBy($this->criteria, $this->orderBy, $itemCountPerPage, $offset);
    }

    /**
     * @return int
     */
    public function count()
    {
        return Module::entityManager()
            ->getUnitOfWork()
            ->getEntityPersister($this->entity)
            ->count();
    }

}