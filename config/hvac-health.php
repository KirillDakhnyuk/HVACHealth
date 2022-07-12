<?php

return [
    'project' => env('STATUS_PROJECT', ''),
    'branch' => env('STATUS_BRANCH', ''),
    'connection' => env('STATUS_CONNECTION', ''),
    'table' => env('STATUS_TABLE', ''),
    'emails' => [
        'from' => '',
        'subject' => '',
        'template' => ''
    ]
];
