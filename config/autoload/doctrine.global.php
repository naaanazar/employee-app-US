<?php

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' =>'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => [
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'employee-prod',
                    'password' => '*(&BHN-0n%$ERT',
                    'dbname'   => 'employee-prod',
                    'encoding' => 'utf8',
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'proxy_dir' => 'config/database/proxy',
                'proxy_namespace' => 'Doctrine\Proxy',
            ]
        ],
        'migrations_configuration' => array(
            'orm_default' => array(
                'directory' => 'config/database/migrations',
                'namespace' => 'Migrations',
                'table' => 'migrations',
            ),
        ),
        'driver' => [
            'application_entities' => [
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [
                    'module/Application/src/Model'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'Application\Model' => 'application_entities',
                ],
            ],
        ],
    ]
];