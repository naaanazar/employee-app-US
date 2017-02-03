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
     * @return JsonModel
     */
    public function jsonAction()
    {
        $json = new JsonModel();
        $json->setVariables(
            [
                'key' => 'value',
                'key2' => 'value',
                'key4' => 'value',
            ]
        );

        return $json;
    }

}