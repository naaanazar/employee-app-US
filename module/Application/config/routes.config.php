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
                'action' => 'index'
            ],
        ],
        'constraints' => [
            'action' => '(?!show)'
        ]
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
            'regex' => '/employee/(?<id>[0-9]+)',
            'defaults' => [
                'controller' => Controller\EmployeeController::class,
                'action'     => 'show'
            ],
            'spec' => '/employee/%id%'
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
            'constraints' => [
                'action' => '[a-z\-]+'
            ],
        ],
    ],
    'dashboard' => [
        'type' => Segment::class,
        'options' => [
            'route' => '/dashboard[/:action][/page/:page]',
            'defaults' => [
                'controller' => Controller\DashboardController::class,
                'action' => 'index',
                'page'   => 1
            ],
        ],
    ]
];