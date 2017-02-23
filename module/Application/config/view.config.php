<?php

namespace Application;

use Application\View\Helper\ScriptStorage;
use Zend\ServiceManager\ServiceManager;

return [
    'factories' => [
        'locale' => function(ServiceManager $serviceManager) {
            return new View\Helper\Locale($serviceManager);
        },
        'route' => function (ServiceManager $serviceManager) {
            $route = new View\Helper\Route($serviceManager->get('Application')->getMvcEvent()->getRouteMatch());
            return $route;
        },
        'scripts' => function (ServiceManager $serviceManager) {
            $config = $serviceManager->get('config');
            if (true === isset($config['scripts'])) {
                $scripts = $config['scripts'];
            } else {
                $scripts = [];
            }

            return new ScriptStorage($scripts);
        }
    ]
];