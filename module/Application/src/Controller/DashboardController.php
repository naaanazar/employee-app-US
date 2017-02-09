<?php

namespace Application\Controller;

use Application\Back\Paginator\Adapter\Doctrine;
use Application\Model\Area;
use Application\Model\RegisterKey;
use Application\Model\Contract;
use Application\Model\WeeklyHours;
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
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('area_value')
        ){

            $value = $this->getRequest()->getPost('area_value');
            $intValue = preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$value) * 1000;

            $json = new JsonModel();
            $area = new Area();
            $area->setIntValue($intValue);
            $area->setValue($value);

            try {
                $this->getEntityManager()->persist($area);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'areas']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save area around');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save area around to database');
            }

            return $json;

        } else {

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

    public function contractAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('name')
        ){

            $name = $this->getRequest()->getPost('name');
            $code  = str_replace(" ", "-", preg_replace('/\s\s+/', ' ', $name));

            $json = new JsonModel();
            $contract = new Contract();
            $contract->setName($name);
            $contract->setCode($code);

            try {
                $this->getEntityManager()->persist($contract);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'contract']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save contract type');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save contract type to database');
            }

            return $json;

        } else {

            $paginator = new Paginator(
                new Doctrine(Contract::class)
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

    public function weeklyHoursAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('value')
        ){

            $value = $this->getRequest()->getPost('value');
            $intValue = preg_replace('/[^\-\d]*(\-?\d*).*/','$1',$value) * 3600;

            $json = new JsonModel();
            $WH = new WeeklyHours();
            $WH->setIntValue($intValue);
            $WH->setValue($value);

            try {
                $this->getEntityManager()->persist($WH);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'weekly-hours']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save area around');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save area around to database');
            }

            return $json;

        } else {

            $paginator = new Paginator(
                new Doctrine(WeeklyHours::class)
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