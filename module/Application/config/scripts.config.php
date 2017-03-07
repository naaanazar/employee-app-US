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
    '((employee\/[a-z0-9]{40})|dashboard\/(|overview))' => [
        'js/module/employee/comments.js'
    ],
    '\/((employee(\/(index))?|(dashboard\/?(statistics|)(\/page\/[0-9]+)?)))$' => [
        'js/module/dashboard/search-employee.js',
        'js/module/dashboard/create-search-request.js',
    ],
    '\/dashboard.+' => [
        'js/module/dashboard/table-sort.js'
    ],
    '((\/dashboard\/?)|(\/employee\/?(edit)?))$' => [
        'js/custom-map.js',
    ]
];