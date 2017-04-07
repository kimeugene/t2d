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
    public function initEmailAuth(ServerRequestInterface $request, ResponseInterface $response)
    {
        $status = 500;
        $message = ["message" => "Failed to send email"];

        try
        {
            $request_body = $request->getParsedBody();
            $email = $request_body['email'];

            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');
            $user = $user_service->createOrUpdateUser(
                $email,
                $this->container->get('settings')['auth_code_ttl']
            );

            if ($user)
            {
                /** @var \App\Services\EmailService $email_service */
                $email_service = $this->container->get('email_service');
                if ($email_service->send_email($email, $user['auth_code']))
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

    public function confirmEmail(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request_body = $request->getParsedBody();
        $code = $request_body["code"];

        if (empty($code))
        {
            $status = 400;
            $message = ["message" => "Missing code"];
        }
        else
        {
            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');
            $result = $user_service->confirmEmail($code);

            if($result == true){
                $status = 200;
                $message = ["message" => "OK"];
            }
            else {
                $status = 400;
                $message = ["message" => "Invalid code"];
            }
        }

        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($message));

        return $response;
    }

    public function getPlates(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getQueryParams();

        if (isset($params['code']) && !empty($params['code']))
        {
            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');

            $user = $user_service->isValid($params['code']);
            if ($user)
            {
                $plates = $user_service->getPlates($user);
                foreach ($plates as $plate)
                {
                    $result['plates'][] = [
                        'plate' => $plate->plate,
                        'created' => $plate->created_at
                    ];
                }
                $result['max_plates_allowed'] = $this->container->get('settings')['max_plates_allowed'];
                $message = $result;
                $status = 200;
            }
            else
            {
                $status = 401;
                $message = ["message" => "Unauthorized"];
            }

        }
        else
        {
            $status = 400;
            $message = "Missing code";
        }


        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($message));

        return $response;
    }

    public function addPlate(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request_body = $request->getParsedBody();
        $code = $request_body['code'];

        $status = 500;
        $message = ["message" => "Cannot add plate"];

        /** @var \App\Services\UserService $user_service */
        $user_service = $this->container->get('user_service');

        $user = $user_service->isValid($code);
        if ($user)
        {
            if ($user_service->addPlate($user, $request_body['plate'], $request_body['state']))
            {
                $status = 201;
                $message = "OK";
            }
        }
        else
        {
            $status = 401;
            $message = ["message" => "Unauthorized"];
        }

        $response = $response->withStatus($status);
        $response->getBody()->write(json_encode($message));

        return $response;

    }

}
