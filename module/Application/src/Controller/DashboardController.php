<?php

namespace Application\Controller;

use Application\Back\Paginator\Adapter\Doctrine;
use Application\Model\Area;
use Application\Model\RegisterKey;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

class DashboardController extends AbstractController
{

    public function init()
    {
        $this->layout('layout/admin');
    }

    public function indexAction()
    {
    }

    public function searchAction()
    {

    }

    public function areasAction()
    {
        $paginator = new Paginator(
            new Doctrine(Area::class)
        );

        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->params('page', 1));

        $view = new ViewModel();
        $view->setVariables(
            [
                'paginator' => $paginator
            ]
        );

        return $view;
    }

    public function registerKeysAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('add')
        ) {
            $json = new JsonModel();

            $registerKey = new RegisterKey();
            $registerKey->setValue(RegisterKey::hashKey());

            try {
                $this->getEntityManager()->persist($registerKey);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'register-keys']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save register key');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save register key to database');
            }

            return $json;

        } else {
            $paginator = new Paginator(
                new Doctrine(RegisterKey::class)
            );

            $paginator->setItemCountPerPage(20);
            $paginator->setCurrentPageNumber($this->params('page', 1));

            $view = new ViewModel();
            $view->setVariables(
                [
                    'paginator' => $paginator
                ]
            );

            return $view;
        }
    }

}