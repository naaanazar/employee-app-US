<?php

namespace Application\Controller;

use Application\Model\Employee;
use Application\Model\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractActionController
{

    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }

    public function testAction()
    {
            /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getEvent()->getApplication()->getServiceManager()->get('Doctrine\ORM\EntityManager');

        $repo = $entityManager->getRepository(User::class);
        $user = $repo->find(70101);

        $employee = new Employee();
        $employee->setUser($user);
        $employee->setAddressLine('asdasd');
        $entityManager->persist($employee);

        $entityManager->flush();

        var_dump($user);exit();

    }
}
