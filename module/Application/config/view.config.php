<?php

namespace Application;

use Zend\ServiceManager\ServiceManager;

return [
    'factories' => [
        'locale' => function(ServiceManager $serviceManager) {
            return new View\Helper\Locale($serviceManager);
        },
        'route' => function (ServiceManager $sm) {
            $route = new View\Helper\Route($sm->get('Application')->getMvcEvent()->getRouteMatch());
            return $route;
        },
    ]
];