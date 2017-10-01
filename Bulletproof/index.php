<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/** @var \RequestHandler\Modules\Application\IApplication $app */
$app = ObjectFactory::create(
    \RequestHandler\Modules\Application\IApplication::class, __DIR__ . '/config.json'
);

/** @var \RequestHandler\Modules\Router\IRouter $router */
$router = ObjectFactory::create(\RequestHandler\Modules\Router\IRouter::class);

$app->run(
    $router
//        ->post('/forum/post', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// C
        ->get('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Thread\GetPost::class)// R
//        ->patch('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// U
//        ->delete('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)  // D
);
