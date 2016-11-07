<?php

return [
    '/create' => [
        \App\Controller\CommentsController::class,
        'create'
    ],
    '/update' => [
        \App\Controller\CommentsController::class,
        'update'
    ],
    '/delete' => [
        \App\Controller\CommentsController::class,
        'delete'
    ],
    '/' => [
        \App\Controller\CommentsController::class,
        'getList'
    ],
];