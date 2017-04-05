<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class UserController extends BaseController
{
    /**
     * Initiate email authentication process, generate random code and send it to the provided email
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param $args
     * @return ResponseInterface|static
     */
    public function init_email_auth(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $status = 500;
        $message = ["message" => "Failed to send email"];

        try
        {
            $request_body = $request->getParsedBody();
            $email = $request_body['email'];

            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');
            $result = $user_service->createOrUpdateUser(
                $email,
                $this->container->get('settings')['auth_code_ttl']
            );
            if ($result)
            {
                /** @var EmailService $email_service */
                $email_service = $this->container->get('email_service');
                if ($email_service->send_email($email, $result['auth_code'], $result['new_user']))
                {
                    $status = 200;
                    $message = ["message" => "OK", "can_resend" => true];
                }
            }
        }
        catch (\Exception $e)
        {
            $this->logger->error(sprintf('%s: %s', "Could not send email", $e->getMessage()));
        }

        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($message));

        return $response;
    }

    public function confirm_email(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $request_body = $request->getParsedBody();
        $code = $request_body["code"];

        $user_service = $this->container->get('user_service');
        $result = $user_service->getUserByCode($code);

        if($result == true){
            $status = 200;
            $message = ["message" => "OK"];
        }
        else {
            $status = 400;
            $message = ["message" => "Not OK"];
        }



        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($message));

        return $response;
    }


}
