<?php

namespace Application\Controller;

use Application\Module;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\AbstractActionController;

/**
 * Class AbstractController
 * @package Application\Controller
 */
abstract class AbstractController extends AbstractActionController
{

    /**
     * @return EntityManager mixed
     */
    public function getEntityManager()
    {
        return $this->getEvent()->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');
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