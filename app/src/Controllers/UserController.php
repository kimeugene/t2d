<?php

namespace App\Controllers;

use App\Models\User;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class UserController extends BaseController
{
    public function init_email_auth(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $this->container->get('logger')->info("Slim-Skeleton '/' route");


        $body = $request->getParsedBody();
        $email = $body['email'];

        $auth_code = bin2hex(random_bytes(20));

        $user = new User();
        $user->email = $email;
        $user->auth_code = $auth_code;
        $user->auth_code_ttl = time() + $this->settings['auth_code_ttl'];

        $user->save();
    }
}
