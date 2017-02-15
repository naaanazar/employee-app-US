<?php

namespace Application\Controller;

use Application\Back\Form\Search\Dashboard\Statistic;
use Application\Back\Paginator\Adapter\Doctrine;
use Application\Model\Area;
use Application\Model\Coordinates;
use Application\Model\Employee;
use Application\Model\RegisterKey;
use Application\Model\Contract;
use Application\Model\Repository\CoordinatesRepository;
use Application\Model\Repository\EmployeeRepository;
use Application\Model\WeeklyHours;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Zend\Paginator\Paginator;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

/**
 * Class DashboardController
 * @package Application\Controller
 */
class DashboardController extends AbstractController
{

    /**
     * @return array
     */
    public function init()
    {
        if ($this->getUser() === null || $this->getUser()->getRole() !== 'admin') {
            return $this->notFoundAction();
        }

        $this->layout('layout/admin');
    }

    /**
     * Index action
     */
    public function indexAction()
    {
    }

    /**
     * Search employees action
     *
     * @return ViewModel
     */
    public function searchAction()
    {
        $criteria = [];

        if (true === $this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();

            /** @var EmployeeRepository $employeesRepository */
            $employeesRepository = $this->getEntityManager()->getRepository(Employee::class);

            $employeesRepository
                ->addExpression('contains', 'name', $post['name'])
                ->addExpression('contains', 'surname', $post['surname'])
                ->addExpression('contains', 'city', $post['city'])
                ->addExpression('contains', 'zip', $post['zip'])
                ->addExpression('eq', 'carAvailable', $post['car_available'])
                ->addExpression('eq', 'drivingLicence', $post['driving_license'])
                ->addExpression('eq', 'areaAround', $this->getEntityManager()->getRepository(Area::class)->find($post['area_around']));


                if( !empty($post['start'])) {
                    $employeesRepository
                        ->addExpression('gt', 'startDate', (new \DateTime ($post['start'])))
                        ->addExpression('lt', 'startDate', (new \DateTime ($post['end'])));
                }

            if (false === empty($post['longitude']) && false === empty($post['latitude'])) {
                $coordinates = (new Coordinates())
                    ->setLatitude($post['latitude'])
                    ->setLongitude($post['longitude']);

                /** @var CoordinatesRepository $coordinatesRepo */
                $coordinatesRepo = $this->getEntityManager()->getRepository(Coordinates::class);

                $coordinates = $coordinatesRepo->getCoordinatesInRange($coordinates);

                $employeesIds = array_map(
                    function ($coordinate) {
                        /** @var Coordinates $coordinate */
                        return $coordinate->getEmployee()->getId();
                    },
                    $coordinates
                );

                $employeesRepository->addExpression('in', 'id', $employeesIds);
            }

            $criteria = $employeesRepository->buildCriteria();
        }

        $paginator = new Paginator(
            new Doctrine(Employee::class, $criteria)
        );

        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->params('page', 1));

        $view = new ViewModel();
        $view->setVariables(
            [
                'paginator'     => $paginator,
                'contracts'     => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                'areas'         => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'weeklyHours'   => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll(),
                'fields'        => $this->getRequest()->getPost()
            ]
        );

        return $view;
    }

    /**
     * Dashboard  configure area around action
     *      *
     * @return ViewModel|array
     */
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

    /**
     * Dashboard  configure area around action
     *
     * @return ViewModel|array
     */
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

    /**
     * Dashboard  configure contracts type action
     *
     * @return ViewModel|array
     */
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

    /**
     * Dashboard  configure weekly hours action
     *
     * @return ViewModel|array
     */
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

    /**
     * Dashboard  show statistics action
     *
     * @return ViewModel
     */
    public function statisticsAction()
    {
        $search = new Statistic($post = $this->getRequest()->getPost());

        return new ViewModel(
            [
                'paginator' => $search->getResult(),
                'post'      => $post
            ]
        );
    }

}