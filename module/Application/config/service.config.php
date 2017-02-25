<?php

namespace Application;

return [
    'factories'=> [
        'auth' => function(\Interop\Container\ContainerInterface $serviceManager) {
            $storage = new Back\Auth\Storage('employee_app');
            $storage->setEntityManager($serviceManager->get('Doctrine\ORM\EntityManager'));

            $authService = new \Zend\Authentication\AuthenticationService();
            $authService->setStorage($storage);
            return $authService;
        },
        'mail' => function (\Zend\ServiceManager\ServiceManager $serviceManager) {
            $config = $serviceManager->get('config');
            $smtpOptions = $config['mail'] ?? [];
            $renderer = $serviceManager->get('Zend\View\Renderer\PhpRenderer');

            return new Back\Mail\Sender($smtpOptions, $renderer);
        }
    ],
];