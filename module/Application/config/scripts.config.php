<?php

namespace Application;

return [
    '.+' => [
        'js/vendor/jquery-3.1.0.min.js',
        'js/vendor/bootstrap.min.js',
        'js/vendor/bootstrap-datepicker.js',
        'js/vendor/jquery.loading.min.js',
        'js/application.js',
    ],
    '((employee\/[a-z0-9]{40})|dashboard\/(search|overview))' => [
        'js/module/employee/comments.js'
    ],
    '^\/((employee(\/index)?|(dashboard\/search(\/page\/[0-9]+)?))(\/)?)$' => [
        'js/module/dashboard/search-employee.js',
        'js/custom-map.js',
    ],
    '^\/dashboard.+' => [
        'js/module/dashboard/table-sort.js'
    ]
];