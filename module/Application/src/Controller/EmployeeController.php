<?php

namespace Application\Controller;

use Application\Back\{
    Service\FileManager,
    Service\ImageManager,
    Form\Employee
};
use Application\Model\{
    Comment, Contract, Area, Image, ReasonRemoval, Repository\EmployeeRepository, SearchRequest, User, SourceApplication, WeeklyHours, Coordinates, Employee as EmployeeModel
};

use Application\Module;
use Application\Model\File;

use Zend\View\Model\{
    JsonModel,
    ViewModel
};
use Application\Back\Form\Search\Employee\GetContributorEmployee;
use Zend\Http\Response;

/**
 * Class EmployeeController
 * @package Application\Controller
 */
class EmployeeController extends AbstractController
{

    /**
     * @inheritdoc
     */
    public function init()
    {
//        $this->restrictNonLoggedIn();
    }

    /**
     * Main Employee action with add form
     *
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->getUser() !== null && $this->getUser()->getRole() === User::ROLE_USER) {

            /** @var EmployeeModel $employee */
            $employee = $this->getEntityManager()
                ->getRepository(EmployeeModel::class)
                ->findOneBy(
                    [
                        'user' => $this->getUser()
                    ]
                );

            if ($employee !== null) {
                return $this->redirect()->toRoute(
                    'show-employee',
                    [
                        'hash' => $employee->getHash()
                    ]
                );
            }
        }

        $view = new ViewModel();

        if ($this->getUser() !== null) {
            $view->setVariable('role', $this->getUser()->getRole());
        }

        $view->setVariables(
            [
                'sources'     => $this->getEntityManager()->getRepository(SourceApplication::class)->findAll(),
                'contracts'   => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                'areas'       => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'weeklyHours' => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll()
            ]
        );

        return $view;
    }

    /**
     * @return ViewModel
     */
    public function editAction()
    {
        if($this->getRequest()->getPost('id')) {
            $data = $this->getRequest()->getPost();

            /** @var EmployeeModel $employee */
            $employee = $this->getEntityManager()
                ->getRepository(EmployeeModel::class)
                ->findOneBy(
                    [
                        'id' => $data['id']
                    ]
                );

            $coordinate = $this->getEntityManager()
                ->getRepository(Coordinates::class)
                ->findOneBy(
                    [
                        'employee' => $employee
                    ]
                );

            $view = new ViewModel();

            $view->setVariables(
                [
                    'role' => $this->getUser()->getRole(),
                    'sources' => $this->getEntityManager()->getRepository(SourceApplication::class)->findAll(),
                    'coordinate' => $coordinate,
                    'contracts' => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                    'areas' => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                    'weeklyHours' => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll(),
                    'employee' => $employee,
                    'action' => 'edit',
                    'id' => $employee->getId()
                ]
            );

            $view->setTemplate('application/employee/index');

            return $view;
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel|array
     */
    public function storeAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {

            $response = new JsonModel();

            $response->setVariables(
                [
                    'errors' => [],
                    'id'     => 0,
                ]
            );

            $data  = $this->getRequest()->getPost()->toArray();
            $data['hourly_rate'] = str_replace(",", ".", $data['hourly_rate']);
            $files = $this->getRequest()->getFiles()->toArray();
            $form = new Employee([]);
            $form->setData(
                array_merge_recursive($data, $files)
            );

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {

                $form->getData();

                /** @var Area $areaAround */
                $areaAround = $this->getEntityManager()
                    ->getRepository(Area::class)
                    ->find($form->get('area_around')->getValue());

                /** @var Contract $contractType */
                $contractType = $this->getEntityManager()
                    ->getRepository(Contract::class)
                    ->find($form->get('contract_type')->getValue());

                /** @var WeeklyHours $weeklyHours */
                $weeklyHours = $this->getEntityManager()
                    ->getRepository(WeeklyHours::class)
                    ->find($form->get('weekly_hours')->getValue());

                /** @var SourceApplication $sourceApplication */
                $sourceApplication = $this->getEntityManager()
                    ->getRepository(SourceApplication::class)
                    ->find($form->get('source')->getValue());

                /** @var EmployeeRepository $employeeRepository */
                $employeeRepository = $this->getEntityManager()->getRepository(EmployeeModel::class);

                if (true === isset($data['id'])) {
                    $employee = $employeeRepository->find($data['id']);
                } else {
                    $employee = new EmployeeModel();
                    $employee->setHash(EmployeeModel::hashKey())
                        ->setCreated(new \DateTime());
                }

                $image = new Image();

                    if (null !== $form->get('image')->getValue()) {
                        $imageManager = new ImageManager();
                        $imageName = $form->getOption('image');

                        $image->setOriginal($imageName);
                        $image->setThumbnail($imageManager->resizeImage(
                            BASE_PATH . DIRECTORY_SEPARATOR . $imageName,
                            128,
                            BASE_PATH . '/img/employee/thumb/' . basename($imageName)
                        ));
                    } else {
                        $image->setOriginal(Image::DEFAULT_IMAGE);
                        $image->setThumbnail(Image::DEFAULT_THUMB);
                    }

                $this->getEntityManager()->persist($image);
                $this->getEntityManager()->flush();

                $employee->setName           ($form->get('name')->getValue())
                    ->setSurname             ($form->get('surname')->getValue())
                    ->setEmail               ($form->get('email')->getValue())
                    ->setAddress             ($form->get('address')->getValue())
                    ->setCity                ($form->get('city')->getValue())
                    ->setZip                 ($form->get('zip')->getValue())
                    ->setMobilePhone         ($form->get('mobile_phone')->getValue())
                    ->setLandlinePhone       ($form->get('landline_phone')->getValue())
                    ->setAreaAround          ($areaAround)
                    ->setContract            ($contractType)
                    ->setWeeklyHoursAvailable($weeklyHours)
                    ->setSourceApplication   ($sourceApplication)
                    ->setStartDate           ((new \DateTime($form->get('start_date')->getValue())))
                    ->setComments            ($form->get('comments')->getValue())
                    ->setHourlyRate          ($form->get('hourly_rate')->getValue())
                    ->setExperience          ((bool)$form->get('experience')->getValue())
                    ->setCarAvailable        ((bool)$form->get('car_available')->getValue())
                    ->setDrivingLicence      ((bool)$form->get('driving_license')->getValue())
                    ->setUpdated(new \DateTime());

                if (null === $employee->getJobStatus()) {
                    $employee->setJobStatus('active');
                }

                if (false === (isset($data['id']) && null == $form->get('image')->getValue())) {
                    $employee->setImage($image);
                }

                if (null !== $this->getUser()) {
                    $employee->setUser($this->getUser());
                } else {
                    $user = new User();
                    $user->setEmail($employee->getEmail());
                    $user->setName(implode(' ', [$employee->getSurname(), $employee->getName()]));
                    $user->setRole(User::ROLE_USER);

                    $password = substr(hash('sha512',rand()),0,12);
                    $user->setPassword(User::hashPassword($password));

                    $this->getEntityManager()->persist($user);
                    $this->getEntityManager()->flush($user);

                    $employee->setUser($user);

                    Module::getMailSender()
                        ->sendMail(
                            Module::translator()->translate('Login details'),
                            $user->getEmail(),
                            'employee/login-details',
                            [
                                'email'    => $user->getEmail(),
                                'password' => $password
                            ]
                        );
                }

                $this->getEntityManager()->persist($employee);

                $fileManager = new FileManager();
                $files = $fileManager->storeFiles($this->getRequest()->getFiles('attachments', []), 'files/employee/' . EmployeeModel::hashKey());

                foreach ($files as $file) {
                    $file->setEmployee($employee);
                    $this->getEntityManager()->persist($file);
                }

                /** @var Coordinates $coordinates */
                $coordinates = $this->getEntityManager()
                    ->getRepository(Coordinates::class)
                    ->findOneBy(
                    [
                        'employee' => $employee
                    ]
                );

                if ($coordinates === null) {
                    $coordinates = new Coordinates();
                    $coordinates
                        ->setEmployee($employee)
                        ->setLongitude($form->get('longitude')->getValue())
                        ->setLatitude($form->get('latitude')->getValue());
                } else {
                    $coordinates->setLongitude($form->get('longitude')->getValue())
                        ->setLatitude($form->get('latitude')->getValue());
                }

                $this->getEntityManager()->persist($coordinates);

                $this->getEntityManager()->flush();

                if (true === $employee instanceof EmployeeModel) {

                    if (null !== $this->getUser()) {
                        $url = $this->url()->fromRoute('show-employee', ['hash' => $employee->getHash()]);
                    } else {
                        $url = $this->url()->fromRoute('index', ['action' => 'information']);
                    }

                    $response->setVariables(
                        [
                            'id'       => $employee->getId(),
                            'redirect' => $url
                        ]
                    );
                }

                if (false === isset($data['id'])) {
                    $requestsRepository = $this->getEntityManager()
                        ->getRepository(SearchRequest::class);

                    $requests = $requestsRepository->findBy(
                        [
                            'found' => false
                        ]
                    );

                    foreach ($requests as $request) {
                        /** @var SearchRequest $request */
                        $params = $request->getParams();
                        $params['lastSearch'] = $request->getLastSearch();

                        if (false === empty($employeesInSearch = $employeeRepository->searchByParams($params))) {
                            Module::getMailSender()->sendMail(
                                Module::translator()->translate('Search request result'),
                                $request->getUser()->getEmail(),
                                'dashboard/search-request-result',
                                [
                                    'employees' => $employeesInSearch,
                                    'searchRequest' => $request
                                ]
                            );
                        }

                        $request->setLastSearch(new \DateTime());
                        $this->getEntityManager()->persist($request);
                    }

                    $this->getEntityManager()->flush();
                }
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * @return array|ViewModel
     */
    public function showAction()
    {
        $hash = $this->params('hash');
        $employee = $this->getEntityManager()
            ->getRepository(EmployeeModel::class)
            ->findOneBy(
                [
                    'hash' => $hash
                ]
            );

        if (null === $employee) {
            return $this->notFoundAction();
        } else {
            $view = new ViewModel();

            $comments = $this->getEntityManager()
                ->getRepository(Comment::class)
                ->findBy(
                    [
                        'employee' => $employee
                    ],
                    [
                        'created' => 'DESC'
                    ]
                );

            $view->setTemplate('application/employee/show.phtml');
            $view->setVariables(
                [
                    'reason' =>  $this->getEntityManager()->getRepository(ReasonRemoval::class)->findAll(),
                    'files' =>  $this->getEntityManager()->getRepository(File::class)->findBy(['employee' => $employee]),
                    'employee' => $employee,
                    'comments' => $comments,
                    'user' => $this->getUser()
                ]
            );
        }

        return $view;
    }

    public function deleteAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $json = new JsonModel();

            if(null !== ($employee = $this->getEntityManager()->getRepository(EmployeeModel::class)
                    ->findOneBy(['hash' => $this->getRequest()->getPost('hash')]))
            ) {
                $reasonRemoval = $this->getEntityManager()
                    ->getRepository(ReasonRemoval::class)
                    ->findOneBy([
                        'name' => $this->getRequest()->getPost('reason')
                    ]);

                $employee->setJobStatus($this->getRequest()->getPost('status'))
                    ->setReasonRemoval($reasonRemoval);

                $this->getEntityManager()->persist($employee);
                $this->getEntityManager()->flush();

                $json->setVariable('status', 'done');
            } else {
                $json->setVariable('status', 'not found');
            }

            return $json;
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function commentAction()
    {

        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $body = $this->getRequest()->getPost('body');
            $employee = $this->getRequest()->getPost('employee');

            $result = new JsonModel();

            if (null !== $body && null !== $employee) {

                /** @var EmployeeModel $employee */
                $employee = $this->getEntityManager()
                    ->getRepository(EmployeeModel::class)
                    ->find($employee);

                $comment = new Comment();
                $comment->setBody($body);
                $comment->setCreated(new \DateTime());
                $comment->setUpdated(new \DateTime());
                $comment->setUser($this->getUser());
                $comment->setEmployee($employee);

                $this->getEntityManager()->persist($comment);
                $this->getEntityManager()->flush();

                $comments = $this->getEntityManager()
                    ->getRepository(Comment::class)
                    ->findBy(
                        [
                            'employee' => $employee
                        ],
                        [
                            'created' => 'DESC'
                        ]
                    );

                $result->setVariables(
                    [
                        'html' => $this->getRenderer()->render(
                            'layout/concern/comments',
                            [
                                'employee' => $employee,
                                'comments' => $comments
                            ]
                        )
                    ]
                );
            }

            return $result;
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function commentDeleteAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            $result = new JsonModel();

            $comment = $this->getEntityManager()
                ->getRepository(Comment::class)
                ->findOneBy(
                    [
                        'id' => $id
                    ]
                );

            if ($comment !== null) {


                $this->getEntityManager()->remove($comment);
                $this->getEntityManager()->flush();

                $result->setVariables(
                    [
                        'result' => true
                    ]
                );

                return $result;
            }
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function commentEditAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');
            $result = new JsonModel();

            $comment = $this->getEntityManager()
                ->getRepository(Comment::class)
                ->findOneBy(
                    [
                        'id' => $id
                    ]
                );

            if ($comment !== null ) {
                $comment->setBody($this->getRequest()->getPost('body'));
                $comment->setUpdated(new \DateTime());
                $comment->setEdited(1);

                $this->getEntityManager()->merge($comment);
                $this->getEntityManager()->flush();

                $result->setVariables(
                    [
                        'result' => true
                    ]
                );
            }

            return $result;
        }

        return $this->notFoundAction();
    }

    /**
     * @return JsonModel
     */
    public function showCommentEditAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $massage = $this->getRequest()->getPost('body');

            return new JsonModel(
                [
                    'html'  => $this->getRenderer()
                        ->render(
                            'layout/concern/comment/edit-field',
                            [
                                'massage' => $massage
                            ]
                        )
                ]
            );
        }

        return $this->notFoundAction();
    }

    /**
     * Add attachments in employee info
     * @return array|JsonModel
     */
    public function addAttachmentsAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {

            $response = new JsonModel();

            $response->setVariables(
                [
                    'errors' => [],
                    'id'     => 0,
                ]
            );

            $data  = $this->getRequest()->getPost();
            $id = $data['id'];

                $employee = $this->getEntityManager()
                    ->getRepository(EmployeeModel::class)
                    ->findOneBy(
                        [
                            'id' => $id
                        ]
                    );

                $fileManager = new FileManager();
                $files = $fileManager->storeFiles($this->getRequest()->getFiles('attachments', []), 'files/employee/' . EmployeeModel::hashKey());

                foreach ($files as $file) {

                    $file->setEmployee($employee);
                    $this->getEntityManager()->persist($file);
                    $this->getEntityManager()->flush();
                }

                $url = $this->url()->fromRoute('show-employee', ['hash' => $employee->getHash()]);

                $response->setVariables(
                    [
                        'id'       => $id,
                        'redirect' => $url
                    ]
                );

            return $response;

        } else {

            return $this->notFoundAction();
        }
    }

    /**
     * @return JsonModel
     */
    public function fileRemoveAction()
    {
        if (true === $this->getRequest()->isXmlHttpRequest()) {
            $id = $this->getRequest()->getPost('id');

            $result = new JsonModel();

            $fileManager = new FileManager();
            $fileRm = $fileManager->remove(BASE_PATH . DIRECTORY_SEPARATOR . $this->getRequest()->getPost('path'));

            /*Remove dir*/
            $path_parts = pathinfo(BASE_PATH . DIRECTORY_SEPARATOR . $this->getRequest()->getPost('path'));
            rmdir($path_parts['dirname']);

            $file = $this->getEntityManager()
                ->getRepository(File::class)
                ->findOneBy(
                    [
                        'id' => $id
                    ]
                );

            if ($file !== null) {
                $this->getEntityManager()->remove($file);
                $this->getEntityManager()->flush();

                $result->setVariables(
                    [
                        'result' =>  $fileRm
                    ]
                );

                return $result;
            }
        }

        return $this->notFoundAction();
    }

    /**
     * @return ViewModel
     */
    public function getContributorEmployeeAction()
    {
        $data = $this->getRequest()->getPost()->toArray();
        $data['page'] = $this->params('page', 1);
        $data['user'] = $this->getUser();
        $search = new GetContributorEmployee($data);

        $view = new ViewModel(
            [
                'paginator' => $search->getResult()
            ]
        );

        $view->setTemplate('application/employee/contributor-employee');

        return $view;
    }
}