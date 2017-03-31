<?php

namespace App\Controllers;



use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class UserController extends BaseController
{
    public function init_email_auth(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        try
        {
            $this->container->get('logger')->info("Slim-Skeleton '/' route");


            $request_body = $request->getParsedBody();
            $email = $request_body['email'];




            $this->container->get('email_service')->send_email($email, $auth_code);
            $status = 200;
        }
        catch (SesException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email (SES)", $e->getMessage()));
        }
        catch (AwsException $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email (AWS)", $e->getMessage()));
        }
        catch (Exception $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email", $e->getMessage()));
        }


        $response = $response->withStatus($status);
        $response = $response->withHeader('Content-Type', 'application/json');
        $response->getBody()->write("OK");

        return $response;

    }
}
