<?php

namespace Application\Controller;

use Application\Back\Controller\Helper\ConfigureActions;
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
use Zend\Stdlib\ArrayUtils;
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
                    'jobStatus' => 'active'
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
            || (null !== $this->getRequest()->getPost('value' || null !== $this->getRequest()->getPost('id')))
        ){
            $redirect = $this->url()->fromRoute('dashboard', ['action' => 'areas']);

            return  (new ConfigureActions)->store($this->getRequest()->getPost(), Area::class, $redirect, 'setNumberConfiguration', 1000);

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
            || (null !== $this->getRequest()->getPost('value' || null !== $this->getRequest()->getPost('id')))
        ){
            $redirect = $this->url()->fromRoute('dashboard', ['action' => 'contract']);

            return  (new ConfigureActions)->store($this->getRequest()->getPost(), Contract::class, $redirect, 'setTextConfiguration');

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
            || (null !== $this->getRequest()->getPost('value' || null !== $this->getRequest()->getPost('id')))
        ){
            $redirect = $this->url()->fromRoute('dashboard', ['action' => 'source-application']);

            return  (new ConfigureActions)->store($this->getRequest()->getPost(), SourceApplicationModel::class, $redirect, 'setTextConfiguration');

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
    public function reasonRemovalAction()
    {
        if (true === $this->getRequest()->isPost()
            && true === $this->getRequest()->isXmlHttpRequest()
            || (null !== $this->getRequest()->getPost('value' || null !== $this->getRequest()->getPost('id')))
        ){
            $redirect = $this->url()->fromRoute('dashboard', ['action' => 'reason-removal']);

            return  (new ConfigureActions)->store($this->getRequest()->getPost(), ReasonRemovalModel::class, $redirect, 'setTextConfiguration');

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
            || (null !== $this->getRequest()->getPost('value' || null !== $this->getRequest()->getPost('id')))
        ){
            $redirect = $this->url()->fromRoute('dashboard', ['action' => 'weekly-hours']);

            return  (new ConfigureActions)->store($this->getRequest()->getPost(), WeeklyHours::class, $redirect, 'setNumberConfiguration', 3600);

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
        $data = $this->getRequest()->getPost()->toArray();

        $search = new Statistic($data);

        $view = new ViewModel(
            [
                'paginator' => $search->getResult(),
                'post'      => $this->getRequest()->getPost()
            ]
        );

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $view->setTemplate('layout/concern/employees');
        }

        return $view;
    }

    /**
     * @return JsonModel
     */
    public function AreaDeleteAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            return  (new ConfigureActions)->detete(Area::class, $id);
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function ContractDeleteAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            return  (new ConfigureActions)->detete(Contract::class, $id);
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function ReasonRemovalDeleteAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            return  (new ConfigureActions)->detete(\Application\Model\ReasonRemoval::class, $id);
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function SourceApplicationDeleteAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            return  (new ConfigureActions)->detete(\Application\Model\SourceApplication::class, $id);
        }

       return $this->notFoundAction();
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
            $searchRequest->setLastSearch(new \DateTime());

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

    public function searchRequestsAction()
    {
        $paginator = new Paginator(
            new Doctrine(SearchRequest::class, [], ['found' => 'ASC'])
        );
        $paginator->setCurrentPageNumber($this->params('page', 1));

        return new ViewModel(
            [
                'paginator' => $paginator
            ]
        );
    }

    /**
     * @return array|JsonModel
     */
    public function searchRequestsSetFoundAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $response = new JsonModel();
            $id = $this->getRequest()->getPost('id');

            $searchRequest = $comment = $this->getEntityManager()
                ->getRepository(SearchRequest::class)
                ->findOneBy(
                    [
                        'id' => $id
                    ]
                );

            $searchRequest->setFound($this->getRequest()->getPost('found'));
            $this->getEntityManager()->merge($searchRequest);
            $this->getEntityManager()->flush($searchRequest);

            $url = $this->url()->fromRoute('dashboard', ['action' => 'search-requests']);;

            $response->setVariables(
                [
                    'redirect' => $url
                ]
            );

            return $response;
        }

        return $this->notFoundAction();
    }

    /**
     * @return array|JsonModel
     */
    public function deleteRequestAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $response = new JsonModel();
            $id = $this->getRequest()->getPost('id');

            $request = $this->getEntityManager()
                ->getRepository(SearchRequest::class)
                ->findOneBy([
                    'id' => $id
                ]);

            $this->getEntityManager()->remove($request);
            $this->getEntityManager()->flush();

            $url = $this->url()->fromRoute('dashboard', ['action' => 'search-requests']);;

            $response->setVariables(
                [
                    'redirect' => $url
                ]
            );

            return $response;
        }

        return $this->notFoundAction();
    }

    /**
     * @return array|ViewModel
     */
    public function showSearchRequestAction()
    {
        $id = $this->params('id');
        $searchRequest = $this->getEntityManager()
            ->getRepository(SearchRequest::class)
            ->findOneBy(
                [
                    'id' => $id
                ]
            );

        if (null === $searchRequest) {
            return $this->notFoundAction();
        } else {

            /** @var Paginator $paginator */
            $paginator = $this->getEntityManager()
                ->getRepository(Employee::class)
                ->searchByParams($searchRequest->getParams(), true);

            $paginator->setCurrentPageNumber($this->params('page', 1));

            return new ViewModel(
                [
                    'searchRequest' => $searchRequest,
                    'paginator'     => $paginator
                ]
            );
        }

    }

    /**
     * @return JsonModel
     */
    public function WeeklyHoursDeleteAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            return  (new ConfigureActions)->detete(\Application\Model\WeeklyHours::class, $id);
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function blockedUserAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            $result = new JsonModel();

            $registerKey = $this->getEntityManager()
                ->getRepository(RegisterKey::class)
                ->findOneBy(
                    [
                        'id' => $id
                    ]
                );

            if ($registerKey !== null) {

                $user = $registerKey->getUser();

                if ($user !== null) {

                    $user->setRole(User::ROLE_BLOCKED);

                    $this->getEntityManager()->merge($user);
                    $this->getEntityManager()->flush();

                    $result->setVariables(
                        [
                            'result' => $user->getName()
                        ]
                    );
                }

                $this->getEntityManager()->remove($registerKey);
                $this->getEntityManager()->flush();
            }

            return $result;
        }

        return $this->notFoundAction();
    }


}