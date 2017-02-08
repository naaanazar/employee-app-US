<?php
namespace Application;

use Application\Back\Translator\Translator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => include 'routes.config.php',
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\EmployeeController::class => InvokableFactory::class,
            Controller\DashboardController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            'application' => __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    'service_manager' => [
        'factories' => [
            'translator' => function (ContainerInterface $serviceManager) {
                // Configure the translator
                $config = $serviceManager->get('config');
                $trConfig = isset($config['translator']) ? $config['translator'] : [];
                $translator = Translator::factory($trConfig);
                return $translator;
            },
        ],
    ],
    'translator' => [
        'locale' => 'en_US',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../locale',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'locales' => [
        'en_US' => 'English',
        'de_DE' => 'Deutsch',
    ]
];
