<?php

namespace Application\Controller;

use Application\Model\Employee as EmployeeModel;
use Application\Back\Form\Employee;
use Application\Model\Contract;
use Application\Model\Area;
use Application\Model\WeeklyHours;
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

        $view->setVariable('contract',
            $this->getEntityManager()
                ->getRepository(Contract::class)
                ->findAll()
        );

        $view->setVariable('area',
            $this->getEntityManager()
                ->getRepository(Area::class)
                ->findAll()
        );

        $view->setVariable('WH',
            $this->getEntityManager()
                ->getRepository(WeeklyHours::class)
                ->findAll()
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
                $employee = new EmployeeModel();
                $employee->setName($form->get('name')->getValue());
                $employee->setSurname($form->get('name')->getValue());
                $employee->setAddress($form->get('address')->getValue());
                $employee->setCity($form->get('city')->getValue());
                $employee->setZip($form->get('zip')->getValue());
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