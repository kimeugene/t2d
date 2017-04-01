<?php

namespace App\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class Preflight extends BaseMiddleware
{
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        // log API call on each request
        $this->container->get('logger')->info("Route: " . $request->getUri());
        $response = $next($request, $response);
        return $response;
    }
}