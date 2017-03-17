<?php

$app->group('/user', function () use ($app) {

    $app->get('/email', function ($request, $response, $args) {
        $this->logger->info("Slim-Skeleton '/' route");

        return json_encode([]);
    });

});

