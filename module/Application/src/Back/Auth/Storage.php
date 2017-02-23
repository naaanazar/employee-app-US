<?php

namespace Application\Back\Auth;

use Application\Model\User;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\Storage\Session;
/**
 * Class Storage
 * @package Application\Storage
 */
class Storage extends Session
{

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var User
     */
    protected $user;

    /**
     * @return null|User
     */
    public function read()
    {
        if (null === $this->user) {
            $this->user = parent::read();
        }
        if (null !== $this->user) {
            $this->user = $this->entityManager->find(User::class, $this->user);
        }
        return $this->user;
    }

    /**
     * @param EntityManager $entityManager
     */
    public function setEntityManager(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

}