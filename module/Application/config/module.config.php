<?php
namespace Application;

use Application\Back\Translator\Translator;
use Interop\Container\ContainerInterface;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route' => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'index' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user[/:action]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'action' => 'index'
                    ],
                ],
            ],
            'show-employee' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => 'employee/:id',
                    'defaults' => [
                        'controller' => Controller\EmployeeController::class,
                        'action'     => 'show'
                    ],
                    'constraints' => [
                        'id' => '[0-9]+'
                    ]
                ]
            ],
            'employee' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/employee[/:action]',
                    'defaults' => [
                        'controller' => Controller\EmployeeController::class,
                        'action' => 'index'
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\UserController::class => InvokableFactory::class,
            Controller\IndexController::class => InvokableFactory::class,
            Controller\EmployeeController::class => InvokableFactory::class,
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
