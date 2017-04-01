<?php

use App\Middleware\Preflight;
$app->add(new Preflight($app->getContainer()));





