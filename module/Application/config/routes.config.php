<?php

namespace Application;

use Zend\Router\Http\Literal;
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