<?php

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Regex;
use Zend\Router\Http\Segment;

return [
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
            'route' => '/user[/:action][/:key]',
            'defaults' => [
                'controller' => Controller\UserController::class,
                'action' => 'login'
            ],
        ],
        'constraints' => [
            'action' => '(?!show)'
        ]
    ],
    'recovery-password' => [
        'type' => Segment::class,
        'options' => [
            'route' => '/recover-password/:hash',
            'defaults' => [
                'controller' => Controller\UserController::class,
                'action' => 'recover-password'
            ],
        ],
    ],
    'recovery-password-cancel' => [
        'type' => Segment::class,
        'options' => [
            'route' => '/recover-password-cancel/:hash',
            'defaults' => [
                'controller' => Controller\UserController::class,
                'action' => 'recover-password-cancel'
            ],
        ],
    ],
    'show-user' => [
        'type'    => Regex::class,
        'options' => [
            'regex' => '/user/(?<id>[0-9]+)',
            'defaults' => [
                'controller' => Controller\UserController::class,
                'action'     => 'show'
            ],
            'spec' => '/user/%id%'
        ]
    ],
    'show-employee' => [
        'type'    => Regex::class,
        'options' => [
            'regex' => '/employee/(?<hash>[a-z0-9]{40})',
            'defaults' => [
                'controller' => Controller\EmployeeController::class,
                'action'     => 'show'
            ],
            'spec' => '/employee/%hash%'
        ]
    ],
    'employee' => [
        'type' => Segment::class,
        'options' => [
            'route' => '/employee[/][:action][/page/:page]',
            'defaults' => [
                'controller' => Controller\EmployeeController::class,
                'action' => 'index',
                'page'   => 1
            ],
            'constraints' => [
                'action' => '[a-z\-]+'
            ],
        ],
    ],
    'show-search-request' => [
        'type'    => Segment::class,
        'options' => [
            'route' => '/dashboard/search-request/:id[/page/:page]',
            'defaults' => [
                'controller' => Controller\DashboardController::class,
                'action'     => 'show-search-request'
            ],
        ]
    ],
    'dashboard' => [
        'type' => Segment::class,
        'options' => [
            'route' => '/dashboard[/][:action][/page/:page]',
            'defaults' => [
                'controller' => Controller\DashboardController::class,
                'action' => 'search',
                'page'   => 1
            ],
        ],
    ],
];