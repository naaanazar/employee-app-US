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

        $requestsRepository = $requests = Module::entityManager()
            ->getRepository(SearchRequest::class);

        $requests = $requestsRepository->findBy(
            [
                'found' => false
            ]
        );

        /** @var EmployeeRepository $employeeRepository */
        $employeeRepository = Module::entityManager()
            ->getRepository(Employee::class);

        foreach ($requests as $request) {
            /** @var SearchRequest $request */
            $employees = $employeeRepository->searchByParams($request->getParams());

            if (false === empty($employees)) {
                $urls = [];

                $router = $this->getEvent()->getRouter();

                $httpRouter = $this->getEvent()
                    ->getApplication()
                    ->getServiceManager()
                    ->get('HttpRouter');

                $this->getEvent()->setRouter($httpRouter);

                foreach ($employees as $employee) {


                    $urls[] = $this->url()->fromRoute(
                        'show-employee',
                        ['hash' => $employee->getHash()],
                        [
                            'force_canonical' => true,
                            'uri'             => new Http('http://employee-app.dev/')
                        ]
                    );
                }

                $this->getEvent()->setRouter($router);

                $message = new Message();
                $message->setSubject('Send Requests');
                $message->setBody(

                    'You have requested search with params:'
                    . '<ul><li>'
                    . implode('</li><li>', $request->getParams())
                    . '</li></ul>'
                    . '<hr> We found some results for you'
                    . '<ul><li>'
                    . implode('</li><li>', $urls)
                    . '</li></ul>'

                );

                $message->setTo($request->getUser()->getEmail());

                try {
                    $transport = new Sendmail();
                    $transport->send($message);
                } catch (\Exception $exception) {
                    echo 'Can not send email to <' . $request->getUser()->getEmail() . '>';
                }

                print_r($urls);

                $request->setFound(true);
                Module::entityManager()->persist($request);
                Module::entityManager()->flush($request);
            }
        }
    }
    
}
