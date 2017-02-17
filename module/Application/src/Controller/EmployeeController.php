<?php

namespace Application\Controller;

use Application\Model\Comment;
use Application\Model\Employee as EmployeeModel;
use Application\Back\Form\Employee;
use Application\Model\Contract;
use Application\Model\Area;
use Application\Model\WeeklyHours;
use Application\Model\Coordinates;
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
     * @return ViewModel
     */
    public function indexAction()
    {
        $view = new ViewModel();

        $view->setVariables(
            [
                'contracts'   => $this->getEntityManager()->getRepository(Contract::class)->findAll(),
                'areas'       => $this->getEntityManager()->getRepository(Area::class)->findAll(),
                'weeklyHours' => $this->getEntityManager()->getRepository(WeeklyHours::class)->findAll()
            ]
        );

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

                $data = $form->getData();

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

                $employee = new EmployeeModel();

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
                    ->setCreated(new \DateTime())
                    ->setUpdated(new \DateTime())
                    ->setHash(EmployeeModel::hashKey());

                if (null !== $form->get('image')->getValue()) {
                    $employee->setImage($form->getOption('image'));
                } else {
                    $employee->setImage('img/user-profile.png');
                }

                if (null !== $this->getUser()) {
                    $employee->setUser($this->getUser());
                }

                $this->getEntityManager()->persist($employee);
                $this->getEntityManager()->flush();

                $coordinates = new Coordinates();
                $coordinates
                    ->setEmployee($employee)
                    ->setLongitude($form->get('longitude')->getValue())
                    ->setLatitude($form->get('latitude')->getValue());

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
                    'employee' => $employee,
                    'comments' => $comments
                ]
            );
        }

        return $view;
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

}