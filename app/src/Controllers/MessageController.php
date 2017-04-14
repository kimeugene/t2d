<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class MessageController extends BaseController
{

   public function addMessage(ServerRequestInterface $request, ResponseInterface $response)
   {
       $status = 500;
       $message = ["message" => "Failed to post message"];

       try
       {
           /** @var \App\Services\UserService $user_service */
           $user_service = $this->container->get('user_service');
           /** @var \App\Services\MessageService $message_service */
           $message_service = $this->container->get('message_service');
           /** @var \App\Services\PhoneService $phone_service */
           $phone_service = $this->container->get('phone_service');

           $params = $request->getParsedBody();

           $user_id = null;

           // owner posting a message
           if (isset($params['code']) && !empty($params['code']))
           {
               $user = $user_service->isValid($params['code']);

               if ($user)
               {
                   $user_id = $user['id'];
                   if ($message_service->addMessage($params['message'], $user_id, $params['plate_id']))
                   {
                       $status = 201;
                       $message = "OK";
                   }
               }
               else
               {
                   $status = 401;
                   $message = "Unauthorized";
               }
           }
           else
           {
               if ($message_service->addMessage($params['message'], $user_id, $params['plate_id']))
               {
                   $phone = $user_service->getUserPhoneByPlate($params['plate_id']);

                   if ($phone)
                   {
                       if ($phone_service->send_text($phone['phone'], $params['message']))
                       {
                           $status = 201;
                           $message = "OK";
                       }
                   }

               }
           }
       }
       catch (\Exception $e)
       {
           $this->container->logger->error(sprintf('%s: %s', "Could not post message", $e->getMessage()));
       }

       $response = $response->withStatus($status);
       $response = $response->withJson($message);

       return $response;
   }

   public function getMessages(ServerRequestInterface $request, ResponseInterface $response)
   {

       $status = 500;
//       $result = ["message" => "Failed to retrieve messages"];

       try
       {
           /** @var \App\Services\UserService $user_service */
           $user_service = $this->container->get('user_service');
           /** @var \App\Services\MessageService $message_service */
           $message_service = $this->container->get('message_service');
           /** @var \App\Services\PhoneService $phone_service */
           $phone_service = $this->container->get('phone_service');

           $params = $request->getQueryParams();


           if (isset($params['plate_id']) && !empty($params['plate_id']))
           {
               $result['plates'] = [];
               $messages = $message_service->getMessages($params['plate_id']);

               foreach ($messages as $message)
               {
                   $result['plates'][$params['plate_id']]['messages'][] = [
                       "message" => $message['text'],
                       "date"    => $message['created_at']
                   ];
               }

               $status = 200;
           }


       }
       catch (\Exception $e)
       {
           $this->container->logger->error(sprintf('%s: %s', "Could not post message", $e->getMessage()));
       }

       $response = $response->withStatus($status);
       $response = $response->withJson($result);

       return $response;
   }

   public function deleteMessage(ServerRequestInterface $request, ResponseInterface $response)
   {

   }

}
