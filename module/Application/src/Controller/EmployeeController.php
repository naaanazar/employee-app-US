<?php

namespace Application\Controller;

use Application\Model\Employee as EmployeeModel;
use Application\Back\Form\Employee;
use Zend\Form\Element\DateTime;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;

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
        return new ViewModel();
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
                $employee = new EmployeeModel();
                $employee->setFullName(
                    implode(
                        ' ',
                        [
                            $form->get('name')->getValue(),
                            $form->get('surname')->getValue()
                        ]
                    )
                );
                $employee->setAddressLine(
                    implode(
                        ', ',
                        [
                            $form->get('city')->getValue(),
                            $form->get('address')->getValue(),
                            $form->get('zip')->getValue()
                        ]
                    )
                );
                $employee->setMobilePhone($form->get('mobile_phone')->getValue());
                $employee->setLandlinePhone($form->get('landline_phone')->getValue());
                // todo: After model create change to actual
                $employee->setAreaAround($form->get('area_around')->getValue());
                // todo: After model create change to actual
                $employee->setContractType($form->get('contract_type')->getValue());
                // todo: After model create change to actual
                $employee->setContractType($form->get('weekly_hours')->getValue());
                // todo: change to actual
                $employee->setStartDate((new \DateTime()));
                $employee->setComments($form->get('comments')->getValue());
                $employee->setHourlyRate($form->get('hourly_rate')->getValue());
                $employee->setExperience((bool)$form->get('experience')->getValue());
                $employee->setCarAvailable((bool)$form->get('car_available')->getValue());
                $employee->setDrivingLicence((bool)$form->get('driving_license')->getValue());
                $this->getEntityManager()->persist($employee);
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
     */
    public function showAction()
    {
        echo 1;

        if (true === $this->getRequest()) {

        }

//        $this->getEntityManager()->find(Employee::class, $this->getRequest());

//        if ($this->getRequest())
    }

}