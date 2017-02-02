<?php

namespace Application\Controller;

use Zend\View\Model\ViewModel;

/**
 * Class IndexController
 * @package Application\Controller
 */
class IndexController extends AbstractController
{

    /**
     * Index action
     * @return ViewModel
     */
    public function indexAction()
    {
        return new ViewModel();
    }
}
