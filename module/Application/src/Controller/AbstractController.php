<?php

namespace Application\Controller;

use Application\Model\User;
use Application\Module;
use Doctrine\ORM\EntityManager;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

/**
 * Class AbstractController
 * @package Application\Controller
 */
abstract class AbstractController extends AbstractActionController
{

    /**
     * Initial method for controllers
     */
    public function init()
    {
    }

    /**
     * @param MvcEvent $e
     * @return mixed
     */
    public function onDispatch(MvcEvent $e)
    {
        $e->getViewModel()->setVariable('user', $this->getUser());
        $this->init();
        return parent::onDispatch($e);
    }

    /**
     * @return EntityManager mixed
     */
    public function getEntityManager()
    {
        return Module::entityManager();
    }

    /**
     * @return AuthenticationService
     */
    public function getAuth()
    {
        return $this->getEvent()
            ->getApplication()
            ->getServiceManager()
            ->get('auth');
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        return $this->getAuth()->getStorage()->read();
    }

    /**
     * @param string $string
     *
     * @return string
     */
    public function translate($string)
    {
        return Module::translator()->translate($string);
    }

}