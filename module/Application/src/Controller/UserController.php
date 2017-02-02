<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Class UserController
 * @package Application\Controller
 */
class UserController extends AbstractActionController
{

    /**
     * Login action
     * @return ViewModel
     */
    public function loginAction()
    {
        if (true === $this->getRequest()->isPost()) {
            // Do login
        }

        return new ViewModel();
    }

    /**
     * @return ViewModel
     */
    public function registerAction()
    {
        return new ViewModel();
    }
    
}