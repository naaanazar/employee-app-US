<?php

namespace Application\Controller;

use Application\Back\Form\Validator\Coordinate;
use Application\Model\Employee as EmployeeModel;
use Application\Back\Form\Employee;
use Application\Model\Contract;
use Application\Model\Area;
use Application\Model\WeeklyHours;
use Application\Model\Coordinates;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;

/**
 * Class EmployeeController
 * @package Application\Controller
 */
class EmployeeController extends AbstractController
{

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

            $data = $this->getRequest()->getPost()->toArray();
            $form = new Employee([]);
            $form->setData($data);

            if (false === $form->isValid()) {
                $response->setVariable('errors', $form->getMessages());
            } else {

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
                    ->setDrivingLicence      ((bool)$form->get('driving_license')->getValue());

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

                $response->setVariable('id', $employee->getId());
            }

            return $response;
        } else {
            return $this->notFoundAction();
        }
    }

    /**
     * Show one Employee action
     *
     * @return ViewModel
     */
    public function showAction()
    {
        $id = $this->params('id');
        $view = new ViewModel();
        $view->setTemplate('application/employee/show.phtml');

        if (null === $id
            || null === (
                $employee = $this->getEntityManager()
                    ->getRepository(EmployeeModel::class)
                    ->find($id)
            )
        ) {
            $this->notFoundAction();
        } else {
            $view->setVariable('employee', $employee);

            if (true === $this->getRequest()->isXmlHttpRequest()) {
                /** @var PhpRenderer $renderer */
                $renderer = $this->getEvent()
                    ->getApplication()
                    ->getServiceManager()
                    ->get('Zend\View\Renderer\PhpRenderer');

                return (new JsonModel())
                    ->setVariables(
                        [
                            'html' => $renderer->render($view)
                        ]
                    );
            }
        }

        return $view;
    }

}