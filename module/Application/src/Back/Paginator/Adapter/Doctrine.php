<?php

namespace Application\Back\Paginator\Adapter;

use Application\Module;

use Doctrine\Common\Collections\Criteria;
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
     * @var array|Criteria
     */
    protected $criteria;

    /**
     * @var array
     */
    protected $orderBy;

    /**
     * @var array
     */
    protected $additionalItems;

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

    public function setAdditionalItems(array $items)
    {
        $this->additionalItems = $items;
    }

    /**
     * @param int $offset
     * @param int $itemCountPerPage
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $repo = Module::entityManager()->getRepository($this->entity);

        if (true === $this->criteria instanceof Criteria) {
            $result = $repo->matching($this->criteria)->toArray();
        } else {
            $result = $repo->findBy($this->criteria, $this->orderBy, $itemCountPerPage, $offset);
        }

        if (null !== $this->additionalItems) {
            $result = array_merge($result, $this->additionalItems);
        }

        return $result;
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