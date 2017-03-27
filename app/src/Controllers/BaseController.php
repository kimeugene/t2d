<?php


namespace App\Controllers;


abstract class BaseController
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

}