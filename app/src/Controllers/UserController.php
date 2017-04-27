<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class UserController extends BaseController
{

    public function initPhoneAuth(ServerRequestInterface $request, ResponseInterface $response)
    {
        $status = 500;
        $message = ["message" => "Failed to send text message"];

        try
        {
            $request_body = $request->getParsedBody();
            $phone_number = $request_body['phone'];

            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');

            /** @var \App\Services\PhoneService $phone_service */
            $phone_service = $this->container->get('phone_service');

            $user = $user_service->isValid($request_body['code']);

            if ($user)
            {
                $phone = $user_service->addPhone($user, $phone_number, $this->container->get('settings')['phone_auth_code_ttl']);

                if ($phone)
                {
                    if ($phone_service->send_text($phone_number, $phone['auth_code']))
                    {
                        $status = 200;
                        $message = ["message" => "OK", "can_resend" => true];
                    }
                }
                else
                {
                    $status = 500;
                    $message = 'Cannot add phone';
                }
            }
            else
            {
                $status = 401;
                $message = ["message" => "Unauthorized"];
            }
        }
        catch (\Exception $e)
        {
            $this->container->logger->error(sprintf('%s: %s', "Could not send text", $e->getMessage()));
        }

        $response = $response->withStatus($status);
        $response = $response->withJson($message);

        return $response;
    }

    public function confirmPhone(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request_body = $request->getParsedBody();
        $verification_code = $request_body["verification_code"];

        if (empty($verification_code))
        {
            $status = 400;
            $message = ["message" => "Missing verification code"];
        }
        else
        {
            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');

            $user = $user_service->isValid($request_body['code']);

            if ($user)
            {
                $result = $user_service->confirmPhone($user, $verification_code);
            }

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
        $response = $response->withJson($message);

        return $response;
    }

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
                    $this->container->get('settings')['email_auth_code_ttl']
                );

                if ($user)
                {
                    /** @var \App\Services\EmailService $email_service */
                    $email_service = $this->container->get('email_service');


                    // check if this email in memcached
                    /** @var \App\Services\MemcachedService $memcached_service */
                    $memcached_service = $this->container->get('memcached_service');
                    $attempts = $memcached_service->get($email);

                    if ($attempts === false)
                    {
                        $this->container->logger->info("Email ( " . $email . ") not found in cache");
                        $memcached_service->set($email, 0, $this->container->get('settings')['email_cache_ttl']);
                        $attempts = 0;
                    }

                    if ($attempts >= $this->container->get('settings')['email_retry_attempts'])
                    {
                        $this->container->logger->info("Found " . $attempts . " attempts for email: " . $email . ", can't send anymore");
                        $status = 403;
                        $message = "Cannot send email anymore, please try later";
                    }
                    else
                    {
                        $this->container->logger->info("Email ( " . $email . ") found in cache, attempts: " . $attempts);

                        if ($email_service->send_email($email, $user['auth_code']))
                        {
                            $attempts ++;
                            $this->container->logger->info("Settings " . $attempts . " attempts for email: " . $email);
                            $memcached_service->replace($email, $attempts);

                            $can_resend = false;
                            if ($attempts < $this->container->get('settings')['email_retry_attempts'])
                            {
                                $can_resend = true;
                            }
                            $status = 200;
                            $message = ["message" => "OK", "can_resend" => $can_resend];
                        }

                    }
                }
        }
        catch (\Exception $e)
        {
            $this->container->logger->error(sprintf('%s: %s', "Could not send email", $e->getMessage()));
        }

        $response = $response->withStatus($status);
        $response = $response->withJson($message);

        return $response;
    }

    /**
     * Verify email by code
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface|static
     */
    public function verifyEmail(ServerRequestInterface $request, ResponseInterface $response)
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
            $result = $user_service->verifyEmail($code);

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
        $response = $response->withJson($message);

        return $response;
    }

    /**
     * Retrieve all user license plates
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface|static
     */
    public function getPlates(ServerRequestInterface $request, ResponseInterface $response)
    {
        $params = $request->getQueryParams();

        if (isset($params['code']) && !empty($params['code']))
        {
            $result['plates'] = [];

            /** @var \App\Services\UserService $user_service */
            $user_service = $this->container->get('user_service');

            $user = $user_service->isValid($params['code']);
            if ($user)
            {
                $plates = $user_service->getPlates($user);
                foreach ($plates as $plate)
                {
                    $result['plates'][] = [
                        'plate_id' => $plate->id,
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
        $response = $response->withJson($message);

        return $response;
    }

    /**
     * Add a license plate to an account
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface|static
     */
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
        $response = $response->withJson($message);

        return $response;

    }

    /**
     * Delete a plate
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface|static
     */
    public function deletePlate(ServerRequestInterface $request, ResponseInterface $response)
    {
        $request_body = $request->getParsedBody();
        $code = $request_body['code'];

        $status = 500;
        $message = ["message" => "Cannot delete plate"];

        /** @var \App\Services\UserService $user_service */
        $user_service = $this->container->get('user_service');

        $user = $user_service->isValid($code);
        if ($user)
        {
            if ($user_service->deletePlate($user, $request_body['plate_id']))
            {
                $status = 200;
                $message = "OK";
            }
        }
        else
        {
            $status = 401;
            $message = ["message" => "Unauthorized"];
        }

        $response = $response->withStatus($status);
        $response = $response->withJson($message);

        return $response;

    }

}
