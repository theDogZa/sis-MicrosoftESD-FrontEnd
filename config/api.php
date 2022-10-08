<?php

return [
    'backend' => [
        'url' => env('BACKEND_HOST', null),
        'service' => [
            'logs' => 'add-logs',
            'getLicense' => 'getLicense',
        ],
        'username' => env('BACKEND_USERNAME', null),
        'password' => env('BACKEND_PASSWORD', null)
    ]
];

?>