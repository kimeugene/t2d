<?php


namespace App\Controllers;

use Interop\Container\ContainerInterface as ContainerInterface;

abstract class BaseController
{
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

}