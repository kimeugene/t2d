<?php

$app->group('/user', function () use ($app) {

    $app->post('/email/auth', 'App\Controllers\UserController:initEmailAuth');

    $app->post('/email/verify', 'App\Controllers\UserController:verifyEmail');

    $app->post('/phone/auth', 'App\Controllers\UserController:initPhoneAuth');

    $app->post('/phone/confirm', 'App\Controllers\UserController:confirmPhone');

    $app->get('/plates', 'App\Controllers\UserController:getPlates');

    $app->post('/plate', 'App\Controllers\UserController:addPlate');

    $app->delete('/plate', 'App\Controllers\UserController:deletePlate');
});

$app->get('/plate/messages', 'App\Controllers\MessageController:getMessages');

$app->post('/plate/message', 'App\Controllers\MessageController:addMessage');

$app->get('/swagger', 'App\Controllers\SwaggerController:generateDocs');


