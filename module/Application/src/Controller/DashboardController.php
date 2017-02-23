<?php

namespace Application\Controller;

use Application\Back\Form\Search\Dashboard\{
    Overview,
    Statistic,
    Areas,
    Contract as ContractBack,
    WeeklyHours as WeeklyHoursBack,
    SourceApplication,
    ReasonRemoval
};

use Application\Model\{
    Area, 
    Coordinates, 
    Employee, 
    RegisterKey, 
    Contract,
    SearchRequest,
    User,
    WeeklyHours,
    ReasonRemoval as ReasonRemovalModel,
    SourceApplication as SourceApplicationModel,
    Repository\EmployeeRepository
};

use Application\Back\Paginator\Adapter\Doctrine;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMInvalidArgumentException;
use Zend\Paginator\Paginator;
use Zend\View\Model\{JsonModel, ViewModel};

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
        if ($this->getUser() === null || $this->getUser()->getRole() !== User::ROLE_ADMIN) {
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
        if (true === $this->getRequest()->isPost()) {
            /** @var EmployeeRepository $employeeRepository */
            $employeeRepository = $this
                ->getEntityManager()
                ->getRepository(Employee::class);

            $paginator = $employeeRepository->searchByParams(
                $this->getRequest()->getPost(),
                true
            );
        } else {
            $paginator = new Paginator(
                new Doctrine(Employee::class, [
                    'deleted' => false
                ])
            );
        }

        $paginator->setItemCountPerPage(20);
        $paginator->setCurrentPageNumber($this->getRequest()->getPost('page', 1));

        if (true === $this->getRequest()->isXmlHttpRequest()) {

            $coordinatesCriteria = new Criteria();
            $coordinatesCriteria->where($coordinatesCriteria->expr()->in('employee', (array)$paginator->getCurrentItems()));
            $coordinates = $this->getEntityManager()
                ->getRepository(Coordinates::class)
                ->matching($coordinatesCriteria);

            $viewHtml = $this->getRenderer()
                ->render(
                    'layout/concern/employees',
                    [
                        'paginator' => $paginator
                    ]
                );

            $coordinates = array_map(
                function ($coordinate) {
                    /** @var Coordinates $coordinate */

                    return [
                        'longitude' => $coordinate->getLongitude(),
                        'latitude'  => $coordinate->getLatitude(),
                        'employee'  => $this->getRenderer()
                            ->render(
                                'layout/concern/map/marker',
                                [
                                    'coordinate' => $coordinate
                                ]
                            ),
                    ];
                },
                $coordinates->toArray()
            );

            $view = new JsonModel(
                [
                    'html'        => $viewHtml,
                    'coordinates' => $coordinates
                ]
            );
        } else {
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
        }

        return $view;
    }

    /**
     * @return array|JsonModel
     */
    public function searchRequestAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $response = new JsonModel();

            $searchRequest = new SearchRequest();
            $searchRequest->setParams($this->getRequest()->getPost('params', []));
            $searchRequest->setFound(false);
            $searchRequest->setUser($this->getUser());

            try {
                $this->getEntityManager()->persist($searchRequest);
                $this->getEntityManager()->flush($searchRequest);

                $response->setVariable('message', 'Successfully created search request');
            } catch (\Exception $exception) {
                $response->setVariable('message', 'Can not save search request');
            }

            return $response;
        }

        return $this->notFoundAction();
    }

    /**
     * Overview application action
     *
     * @return ViewModel
     */
    public function overviewAction()
    {
        $search = new Overview($post = $this->getRequest()->getPost());

        return new ViewModel(
            [
                'paginator' => $search->getResult(),
                'areas'         => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'fields'        => $this->getRequest()->getPost()
            ]
        );

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
            $search = new Areas($post = $this->getRequest()->getPost());

            return new ViewModel(
                [
                    'paginator' => $search->getResult()
                ]
            );
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
            $registerKey->setRole($this->getRequest()->getPost('role'), User::ROLE_USER);

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
            $search = new ContractBack($post = $this->getRequest()->getPost());

            return new ViewModel(
                [
                    'paginator' => $search->getResult()
                ]
            );
        }
    }

    /**
     * Dashboard  configure source application action
     *
     * @return ViewModel|array
     */
    public function sourceApplicationAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('name')
        ){
            $name = $this->getRequest()->getPost('name');
            $code  = str_replace(" ", "-", preg_replace('/\s\s+/', ' ', $name));

            $json = new JsonModel();
            $source = new SourceApplicationModel();
            $source->setName($name);
            $source->setCode($code);

            try {
                $this->getEntityManager()->persist($source);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'source-application']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save source application');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save source application to database');
            }

            return $json;

        } else {
            $search = new SourceApplication($post = $this->getRequest()->getPost());

            return new ViewModel(
                [
                    'paginator' => $search->getResult()
                ]
            );
        }
    }

    /**
     * Dashboard  configure whyDelete action
     *
     * @return ViewModel|array
     */
    public function ReasonRemovalAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            && null !== $this->getRequest()->getPost('name')
        ){
            $name = $this->getRequest()->getPost('name');
            $code  = str_replace(" ", "-", preg_replace('/\s\s+/', ' ', $name));

            $json = new JsonModel();
            $source = new ReasonRemovalModel();
            $source->setName($name);
            $source->setCode($code);

            try {
                $this->getEntityManager()->persist($source);
                $this->getEntityManager()->flush();

                $json->setVariable(
                    'redirect',
                    $this->url()->fromRoute('dashboard', ['action' => 'reason-removal']));
            } catch (ORMInvalidArgumentException $exception) {
                $json->setVariable('message', 'Invalid data to save why delete');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save why delete to database');
            }

            return $json;

        } else {
            $search = new ReasonRemoval($post = $this->getRequest()->getPost());

            return new ViewModel(
                [
                    'paginator' => $search->getResult()
                ]
            );
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
                $json->setVariable('message', 'Invalid data to save weekly-hours');
            } catch (OptimisticLockException $exception) {
                $json->setVariable('message', 'Can not save weekly-hours to database');
            }

            return $json;

        } else {
            $search = new WeeklyHoursBack($post = $this->getRequest()->getPost());

            return new ViewModel(
                [
                    'paginator' => $search->getResult()
                ]
            );
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