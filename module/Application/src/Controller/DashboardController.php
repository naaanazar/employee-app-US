<?php

namespace Application\Controller;

use Application\Back\Paginator\Adapter\Doctrine;
use Application\Model\Area;
use Zend\Paginator\Paginator;
use Zend\View\Model\ViewModel;

class DashboardController extends AbstractController
{

    public function init()
    {
        $this->layout('layout/admin');
    }

    public function indexAction()
    {
    }

    public function searchAction()
    {

    }

    public function areasAction()
    {
        $paginator = new Paginator(
            new Doctrine(Area::class)
        );

        $paginator->setItemCountPerPage(1);
        $paginator->setCurrentPageNumber($this->params('page', 1));

        $view = new ViewModel();
        $view->setVariables(
            [
                'paginator' => $paginator
            ]
        );

        return $view;
    }

}