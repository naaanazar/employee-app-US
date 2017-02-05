<?php

namespace Application\Controller;

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