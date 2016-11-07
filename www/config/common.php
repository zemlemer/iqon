<?php
return [
    'templates' => [
        'adapter' => \App\ViewAdapters\TwigAdapter::class,
        'path'  => __DIR__. '/../views',
        'debug' => env('templates_debug', false),
        'compiled_path' => false,
    ],
    'routes' => require_once ('routes.php'),
    'db' => require_once ('db.php'),
];