<?php

namespace Application\Controller;

use Application\Back\Service\FileManager;
use Application\Back\Service\ImageManager;
use Application\Model\Comment;
use Application\Model\ReasonRemoval;
use Application\Model\SourceApplication;
use Application\Model\Employee as EmployeeModel;
use Application\Back\Form\Employee;
use Application\Model\Contract;
use Application\Model\Area;
use Application\Model\Image;
use Application\Model\User;
use Application\Model\WeeklyHours;
use Application\Model\Coordinates;
use Zend\Http\Response;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

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
        $this->restrictNonLoggedIn();
    }

    /**
     * Main Employee action with add form
     *
     * @return Response|ViewModel
     */
    public function indexAction()
    {
        if ($this->getUser()->getRole() === User::ROLE_USER) {

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

        $view->setVariables(
            [
                'role'        => $this->getUser()->getRole(),
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

        $data  = $this->getRequest()->getPost();
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
                'sources'     => $this->getEntityManager()->getRepository(SourceApplication::class)->findAll(),
                'coordinate'  => $coordinate,
                'contracts'   => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                'areas'       => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'weeklyHours' => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll(),
                'employee'    => $employee,
                'action'      => 'edit',
                'id'          => $employee->getId()
            ]
        );

        $view->setTemplate('application/employee/index');

        return $view;
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

                if (true === isset($data['id'])) {
                    $employee = $this->getEntityManager()->getRepository(EmployeeModel::class)->find($data['id']);
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
                    ->setStartDate           ((new \DateTime($form->get('start_date')->getValue())))
                    ->setComments            ($form->get('comments')->getValue())
                    ->setHourlyRate          ($form->get('hourly_rate')->getValue())
                    ->setExperience          ((bool)$form->get('experience')->getValue())
                    ->setCarAvailable        ((bool)$form->get('car_available')->getValue())
                    ->setDrivingLicence      ((bool)$form->get('driving_license')->getValue())
                    ->setUpdated(new \DateTime());

                if (null === $employee->isDeleted()) {
                    $employee->setDeleted(false);
                }

                if (false === (isset($data['id']) && null == $form->get('image')->getValue())) {
                    $employee->setImage($image);
                }

                if (null !== $this->getUser()) {
                    $employee->setUser($this->getUser());
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

                    $url = $this->url()->fromRoute('show-employee', ['hash' => $employee->getHash()]);

                    $response->setVariables(
                        [
                            'id'       => $employee->getId(),
                            'redirect' => $url
                        ]
                    );
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
                    'employee' => $employee,
                    'comments' => $comments
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
                $employee->setDeleted(true);
                $this->getEntityManager()->persist($employee);
                $this->getEntityManager()->flush();

                $json->setVariable('status', 'deleted');
            } else {
                $json->setVariable('status', 'not deleted');
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
    }

}