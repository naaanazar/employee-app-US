<?php

namespace Application\Controller;

use Application\Back\{
    Form\StepFive, Form\StepFour, Form\StepOne, Form\StepTwo, Form\StepThree, Service\FileManager, Service\ImageManager, Form\Employee
};

use Application\Model\{
    Comment, Contract, Area, Employer, Image, ReasonRemoval, Repository\EmployeeRepository, SearchRequest, Test, User, SourceApplication, WeeklyHours, Coordinates, Employee as EmployeeModel
};

use Application\Module;
use Application\Model\File;

use Zend\View\Model\{
    JsonModel,
    ViewModel
};

use Zend\Session\Container;
use Application\Back\Form\Search\Employee\GetContributorEmployee;
use Zend\Http\Response;

/**
 * Class EmployeeController
 * @package Application\Controller
 */
class EmployeeController extends AbstractController
{
    public static  $formData;

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

        $typingTest = new Container('typingTest');
        $typingTest->offsetUnset('typingTest');

        $storageStep1 = new Container('stepOne');
        $storageStep1->offsetUnset('stepOne');

        $storageStep2 = new Container('stepTwo');
        $storageStep2->offsetUnset('stepTwo');

        $storageStep3 = new Container('stepThree');
        $storageStep3->offsetUnset('stepThree');

        $storageStep4 = new Container('StepFour');
        $storageStep4->offsetUnset('StepFour');


        $view = new ViewModel();

        if ($this->getUser() !== null) {
            $view->setVariable('role', $this->getUser()->getRole());
        }

        $view->setVariables(
            [
                'sources'     => $this->getEntityManager()->getRepository(SourceApplication::class)->findAll(),
                'contracts'   => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                'areas'       => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'weeklyHours' => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll(),
                'expireenceSelect' => EmployeeModel::EXPIRIENCE_SELECT,
                'positionApplyingSelect' => EmployeeModel::POSITION_APPLING_SELECT,
                'locationSelect' => EmployeeModel::LOCATION_SELECT,
                'sratesSelect' => EmployeeModel::STATES_SELECT
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

            $employerList = $this->getEntityManager()
                ->getRepository(Employer::class)
                ->findBy(
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
                    'id' => $employee->getId(),
                    'employerList' => $employerList,
                    'expireenceSelect' => EmployeeModel::EXPIRIENCE_SELECT,
                    'positionApplyingSelect' => EmployeeModel::POSITION_APPLING_SELECT,
                    'locationSelect' => EmployeeModel::LOCATION_SELECT,
                    'sratesSelect' => EmployeeModel::STATES_SELECT

                ]
            );

            $view->setTemplate('application/employee/index');

            return $view;
        }

        return $this->notFoundAction();
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

            $employerList = $this->getEntityManager()
                ->getRepository(Employer::class)
                ->findBy(
                    [
                        'employee' => $employee
                    ]
                );

            $testList = $this->getEntityManager()
                ->getRepository(Test::class)
                ->findBy(
                    [
                        'employee' => $employee
                    ]
                );


            $view->setTemplate('application/employee/show.phtml');
            $view->setVariables(
                [
                    'reason' =>  $this->getEntityManager()->getRepository(ReasonRemoval::class)->findAll(),
                    'files' =>  $this->getEntityManager()->getRepository(File::class)->findBy(['employee' => $employee]),
                    'employee' => $employee,
                    'comments' => $comments,
                    'user' => $this->getUser(),
                    'employerList' => $employerList,
                    'testList' => $testList
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

    /**
     * @return array|JsonModel
     */
    public function stepOneCheckAction()
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
            $form = new StepOne([]);
            $form->setData($data);

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {
                $response->setVariable('errors',true);

                $storage = new Container('stepOne');
                $storage->offsetSet('stepOne', $form->getData());
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * @return array|JsonModel
     */
    public function stepTwoCheckAction()
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

            /** @var EmployeeRepository $employeeRepository */
            $employeeRepository = $this->getEntityManager()->getRepository(EmployeeModel::class);

            if (true === isset($data['id'])) {
                $employee = $employeeRepository->find($data['id']);
            } else {
                $employee = new EmployeeModel();
                $employee->setHash(EmployeeModel::hashKey())
                    ->setCreated(new \DateTime());
            }

            $form = new StepTwo(
                [
                    'allowed_emails' => [$employee->getEmail()]
                ]
            );

            //$form = new StepTwo([]);
            $form->setData($data);

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {
                $response->setVariable('errors',true);

                $storage = new Container('stepTwo');
                $storage->offsetSet('stepTwo', $form->getData());
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * @return array|JsonModel
     */
    public function stepThreeCheckAction()
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
            $form = new StepThree([]);
            $form->setData($data);

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {
                $response->setVariable('errors',true);

                $storage = new Container('stepThree');
                $storage->offsetSet('stepThree', $form->getData());
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * @return array|JsonModel
     */
    public function stepFourCheckAction()
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
            $form = new StepFour([]);
            $form->setData($data);

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {
                $response->setVariable('errors',true);

                $storage = new Container('StepFour');
                $formData = $storage->offsetExists('StepFour') ? $storage->offsetGet('StepFour') : [];

                $formData[$data['id_ex']] = $form->getData();

                $storage->offsetSet('StepFour', $formData);
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * @return array|JsonModel
     */
    public function typingTestAction()
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

            $response->setVariable('errors',true);

            $storage = new Container('typingTest');
            $formData = $storage->offsetExists('typingTest') ? $storage->offsetGet('typingTest') : [];

            $formData[$data['id_test']] = $data;

            $storage->offsetSet('typingTest', $formData);

            $response->setVariable('errors',$formData);


            return $response;
        } else {
            return $this->notFoundAction();
        }
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

            $data  = $this->getRequest()->getPost();
            $data['hourly_rate'] = str_replace(",", ".", $data['hourly_rate']);

            /** @var EmployeeRepository $employeeRepository */
            $employeeRepository = $this->getEntityManager()->getRepository(EmployeeModel::class);

            if (true === isset($data['id'])) {
                $employee = $employeeRepository->find($data['id']);
            } else {
                $employee = new EmployeeModel();
                $employee->setHash(EmployeeModel::hashKey())
                    ->setCreated(new \DateTime());;
            }

            $storageTypingTest = new Container('typingTest');
            $typingTest = $storageTypingTest->offsetExists('typingTest') ? $storageTypingTest->offsetGet('typingTest') : false;

            $storageStep1 = new Container('stepOne');
            $step1 = $storageStep1->offsetExists('stepOne') ? $storageStep1->offsetGet('stepOne') : false;

            $storageStep2 = new Container('stepTwo');
            $step2 = $storageStep2->offsetExists('stepTwo') ? $storageStep2->offsetGet('stepTwo') : false;

            $storageStep3 = new Container('stepThree');
            $step3 = $storageStep3->offsetExists('stepThree') ? $storageStep3->offsetGet('stepThree') : false;

            $storageStep4 = new Container('StepFour');
            $step4 = $storageStep4->offsetExists('StepFour') ? $storageStep4->offsetGet('StepFour') : false;


            $form = new StepFive([]);
            $form->setData($data);

            if (false === $form->isValid() || false == $step1 || false == $step2 || false == $step3) {
                $response->setVariable('errors', $form->getMessages());
            } else {
                $response->setVariable('errors',true);

                $form->getData();

                /** @var Area $areaAround */
                $areaAround = $this->getEntityManager()
                    ->getRepository(Area::class)
                    ->find($step1['area_around']);

                /** @var WeeklyHours $weeklyHours */
                $weeklyHours = $this->getEntityManager()
                    ->getRepository(WeeklyHours::class)
                    ->find($step3['weekly_hours']);

                /** @var SourceApplication $sourceApplication */
                $sourceApplication = $this->getEntityManager()
                    ->getRepository(SourceApplication::class)
                    ->find($form->get('source')->getValue());


                $image = new Image();
                //image test field not load before
                $image->setOriginal(Image::DEFAULT_IMAGE);
                $image->setThumbnail(Image::DEFAULT_THUMB);

                $this->getEntityManager()->persist($image);
                $this->getEntityManager()->flush($image);

                $employee->setName           ($step2['name'])
                    ->setSurname             ($step2['surname'])
                    ->setAddress             ($step2['address'])
                    ->setAddressTwo          ($step2['address_two'])
                    ->setCity                ($step2['city'])
                    ->setState               ($step2['state'])
                    ->setZip                 ($step2['zip'])
                    ->setMobilePhone         ($step2['mobile_phone'])
                    ->setLandlinePhone       ($step2['landline_phone'])
                    ->setEmail               ($step2['email'])


                    ->setPositionApplying     ($step1['position_applying'])
                    ->setLocation             ($step1['location'])
                    ->setAreaAround           ($areaAround)
                    ->setWorkedMlob           ($step1['worked_mlob'])


                    ->setWeeklyHoursAvailable           ($weeklyHours)
                    ->setStartDate                      ((new \DateTime($step3['start_date'])))
                    ->setWorkWeekends                   ($step3['work_weekends'])
                    ->setCustomerServiceExpierence      ($step3['customer_service_expierence'])
                    ->setBusinessOperationsExpierence   ($step3['business_operations_expierence'])
                    ->setManagementExpierence           ($step3['management_expierence'])
                    ->setExpierenceWord                 ($step3['expierence_word'])
                    ->setExpierenceExel                 ($step3['expierence_exel'])
                    ->setExpierenceKeypad               ($step3['expierence_keypad'])


                    ->setHourlyRate                     ($form->get('hourly_rate')->getValue())
                    ->setSourceApplication              ($sourceApplication)
                    ->setDelinquentOrWaived             ($form->get('delinquent_or_waived')->getValue())
                    ->setCriminalBackground             ($form->get('criminal_background')->getValue())

                    ->setUpdated(new \DateTime());

                if (null === $employee->getJobStatus()) {
                    $employee->setJobStatus('active');
                }

                //image test field not load before
                if (false === (isset($data['id']))) {
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

                if(false !== $step4) {

                    foreach ($step4 as $key) {
                        $employer = new Employer();

                        $employer->setName($key['name_ex'])
                            ->setEmployee($employee)
                            ->setCity($key['city_ex'])
                            ->setState($key['state_ex'])
                            ->setYearsEmployed($key['years_employed_ex'])
                            ->setStart($key['start_ex'])
                            ->setEnd($key['end_ex'])
                            ->setComments($key['comments_ex']);

                        $this->getEntityManager()->persist($employer);
                        $this->getEntityManager()->flush($employer);
                    }
                }

                if (false === isset($data['id'])) {

                    if (false !== $typingTest) {

                        foreach ($typingTest as $key) {

                            $test = new Test();

                            $test->setEmployee($employee)
                                ->setNetWPM($key['net_wpm'])
                                ->setGrossWPM($key['gross_wpm'])
                                ->setErrors($key['errors'])
                                ->setAccuracy($key['accuracy'])
                                ->setCreated(new \DateTime())
                                ->setTime($key['time']);


                            $this->getEntityManager()->persist($test);
                            $this->getEntityManager()->flush($test);
                        }
                    }
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
                        ->setLongitude($step2['longitude'])
                        ->setLatitude($step2['latitude']);
                } else {
                    $coordinates->setLongitude($step2['longitude'])
                        ->setLatitude($step2['latitude']);
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

                $storageTypingTest->offsetUnset('typingTest');
                $storageStep1->offsetUnset('stepOne');
                $storageStep2->offsetUnset('stepTwo');
                $storageStep3->offsetUnset('stepThree');
                $storageStep4->offsetUnset('StepFour');
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }
}