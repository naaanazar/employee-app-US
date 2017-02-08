<?php

return [
    'factories'=> [
        'auth' => function(\Interop\Container\ContainerInterface $serviceManager) {
            $storage = new \Application\Back\Auth\Storage('employee_app');
            $storage->setEntityManager($serviceManager->get('Doctrine\ORM\EntityManager'));

            $authService = new \Zend\Authentication\AuthenticationService();
            $authService->setStorage($storage);
            return $authService;
        },
    ],
];