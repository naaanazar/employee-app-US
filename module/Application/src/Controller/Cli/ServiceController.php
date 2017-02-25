<?php

namespace Application\Controller\Cli;

use Application\Model\Employee;
use Application\Model\Repository\EmployeeRepository;
use Application\Model\SearchRequest;
use Application\Module;
use Zend\Mail\Message;
use Zend\Mail\Transport\Sendmail;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Uri\Http;

/**
 * Class ServiceController
 * @package Application\Controller\Cli
 */
class ServiceController extends AbstractActionController
{

    public function sendSearchRequestsAction()
    {


    }
    
}
