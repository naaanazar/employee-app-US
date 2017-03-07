<?php
namespace Application;

use Application\Back\Translator\Translator;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => include 'routes.config.php',
    ],
    'console' => include 'console.config.php',
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\EmployeeController::class => InvokableFactory::class,
            Controller\DashboardController::class => InvokableFactory::class,
            Controller\Cli\ServiceController::class => InvokableFactory::class
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
    'mail' => [
        'name'              => 'gmail.com',
        'host'              => 'smtp.gmail.com',
        'port'              => 587, // Notice port change for TLS is 587
        'connection_class'  => 'plain',
        'connection_config' => array(
            'username' => 'vitalii.krushelnytskyi@gmail.com',
            'password' => 'phgtrsbetvdoqcxr',
            'ssl'      => 'tls',
        ),
    ],
    'translator' => [
        'locale' => 'de_DE',
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => __DIR__ . '/../locale',
                'pattern' => '%s.mo',
            ],
        ],
    ],
    'locales' => [
        'de_DE' => 'Deutsch',
        'en_US' => 'English',
        'fr_FR' => 'Francais',
    ],
    'fixtures' => [
        // Format below
        // Country,City,AccentCity,Region,Population,Latitude,Longitude
        'cities' => [
            __DIR__ . '/../../../data/bin/fixtures/worldcities.txt'
        ]
    ],
    'scripts' => include __DIR__ . '/scripts.config.php'
];
