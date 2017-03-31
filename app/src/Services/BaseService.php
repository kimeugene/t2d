<?php

namespace App\Services;

class BaseService
{
    protected $logger;

    public function __construct($logger)
    {
        $this->logger = $logger;
    }
}