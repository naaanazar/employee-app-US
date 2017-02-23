<?php

namespace Application;

return [
    'router' => [
        'routes' => [
            'list-users' => [
                'options' => [
                    'route'    => 'send-search-requests',
                    'defaults' => [
                        'controller' => Controller\Cli\ServiceController::class,
                        'action'     => 'send-search-requests'
                    ]
                ]
            ]
        ]
    ]
];