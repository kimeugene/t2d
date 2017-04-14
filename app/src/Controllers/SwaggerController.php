<?php

namespace App\Controllers;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;


class SwaggerController extends BaseController
{

    public function generateDocs(ServerRequestInterface $request, ResponseInterface $response)
    {
        $swagger = \Swagger\scan(__DIR__ . '/../');

        $response = $response->withStatus(200);
        $response = $response->withJson($swagger);

        return $response;

    }

}


/**
 * @SWG\Swagger(
 *     schemes={"http","https"},
 *     host="",
 *     basePath="/",
 *     @SWG\Info(
 *         version="1.0.0",
 *         title="Cartexted API",
 *         description="",
 *         termsOfService="",
 *         @SWG\Contact(
 *             email=""
 *         ),
 *         @SWG\License(
 *             name="",
 *             url=""
 *         )
 *     ),
 *     @SWG\ExternalDocumentation(
 *         description="",
 *         url=""
 *     )
 * )
 */

//
//
//                                  ROUTES
//
//
//

/**
 * @SWG\Post(
 *     path="/user/email/verify",
 *     tags={"User APIs"},
 *     summary="Authenticate existing user or create new user",
 *     @SWG\Parameter(
 *         name="email",
 *         in="body",
 *         description="User email",
 *         required=true,
 *         @SWG\Schema(ref="#/definitions/CodeParameter")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Email confirmed"
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid code"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Post(
 *     path="/user/email/auth",
 *     tags={"User APIs"},
 *     summary="Initiate authentication - generate a unique code and send it to the given email address",
 *     @SWG\Parameter(
 *      name="email",
 *      in="body",
 *      description="User email",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/EmailParameter")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Email sent"
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid email"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Post(
 *     path="/user/phone/auth",
 *     tags={"User APIs"},
 *     summary="Initiate authentication - generate a unique code and send it to the given phone number",
 *     @SWG\Parameter(
 *      name="phone",
 *      in="body",
 *      description="User phone number",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/PhoneParameter")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Text sent"
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid phone number"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Post(
 *     path="/user/phone/confirm",
 *     tags={"User APIs"},
 *     summary="Confirm phone number",
 *     @SWG\Parameter(
 *      name="code",
 *      in="body",
 *      description="Auth code sent to user",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/PhoneCodeParameter")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Phone confirmed"
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid code"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Get(
 *     path="/user/plates",
 *     tags={"User APIs"},
 *     summary="Retrieve user plates",
 *     @SWG\Parameter(
 *      name="code",
 *      in="query",
 *      description="Auth code sent to user",
 *      required=true,
 *      type="string",
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Success",
 *         @SWG\Property(
 *             type="object",
 *             @SWG\Property(
 *                 property="plates",
 *                 type="array",
 *                 @SWG\Items(ref="#/definitions/Plate"),
 *             ),
 *             @SWG\Property(
 *                 property="max_plates_allowed",
 *                 type="number",
 *             ),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response="401",
 *         description="Unauthorized"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Post(
 *     path="/user/plate",
 *     tags={"User APIs"},
 *     summary="Add a new plate",
 *     @SWG\Parameter(
 *      name="plate",
 *      in="body",
 *      description="Plate parameters",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/PlateParameters")
 *     ),
 *     @SWG\Response(
 *         response="201",
 *         description="Plate created"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Delete(
 *     path="/user/plate",
 *     tags={"User APIs"},
 *     summary="Delete a plate",
 *     @SWG\Parameter(
 *      name="plate",
 *      in="body",
 *      description="Delete plate parameters",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/DeletePlateParameters")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Plate deleted"
 *     ),
 *     @SWG\Response(
 *         response="400",
 *         description="Invalid parameters"
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */


/**
 * @SWG\Get(
 *     path="/plate/messages",
 *     tags={"Public APIs"},
 *     summary="Retrieve plate messages",
 *     @SWG\Parameter(
 *      name="plate_id",
 *      in="query",
 *      description="Specify plate if you want messages only for that plate",
 *      required=false,
 *      type="string",
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Success",
 *         @SWG\Property(
 *             type="object",
 *             @SWG\Property(
 *                 property="plates",
 *                 type="array",
 *                 @SWG\Items(ref="#/definitions/PlateWithMessages"),
 *             ),
 *         ),
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),
 * )
 */

/**
 * @SWG\Post(
 *     path="/plate/message",
 *     tags={"Public APIs"},
 *     summary="Post a plate message",
 *     @SWG\Parameter(
 *      name="plate",
 *      in="body",
 *      description="Plate message parameters",
 *      required=true,
 *      @SWG\Schema(ref="#/definitions/PlateMessageParameters")
 *     ),
 *     @SWG\Response(
 *         response="200",
 *         description="Success",
 *     ),
 *     @SWG\Response(
 *         response="500",
 *         description="Internal server error"
 *     ),

 * )
 */

//
//
//                                  DEFINITIONS
//
//
//

/**
 * @SWG\Definition(
 *     definition="PlateMessageParameters",
 *      required={"plate_id", "message"},
 *     @SWG\Property(
 *         property="code",
 *         description="Optional auth code (owner posting)",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="message",
 *         description="Message to post",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="plate_id",
 *         description="License plate to post the message to",
 *         type="string"
 *     )
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="PlateParameters",
 *      required={"code", "plate", "state"},
 *     @SWG\Property(
 *         property="code",
 *         description="Auth code to identify the user",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="plate",
 *         description="License plate to add",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="state",
 *         description="State where license plate is registered",
 *         type="string"
 *     )
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="DeletePlateParameters",
 *      required={"code", "plate_id"},
 *     @SWG\Property(
 *         property="code",
 *         description="Auth code to identify the user",
 *         type="string"
 *     ),
 *     @SWG\Property(
 *         property="plate_id",
 *         description="License plate to delete",
 *         type="string"
 *     )
 * )
 *
 */


/**
 * @SWG\Definition(
 *     definition="EmailParameter",
 *      required={"email"},
 *     @SWG\Property(
 *      property="email",
 *      type="string",
 *     ),
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="CodeParameter",
 *      required={"code"},
 *     @SWG\Property(
 *      property="code",
 *      type="string",
 *     ),
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="PhoneCodeParameter",
 *      required={"code", "verification_code"},
 *     @SWG\Property(
 *      property="code",
 *      type="string",
 *     ),
 *     @SWG\Property(
 *      property="verification_code",
 *      type="string",
 *     ),
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="PhoneParameter",
 *      required={"phone"},
 *     @SWG\Property(
 *      property="phone",
 *      type="string",
 *     ),
 *     @SWG\Property(
 *      property="code",
 *      type="string",
 *     ),
 * )
 *
 */

/**
 * @SWG\Definition(
 *     definition="Plate",
 *         @SWG\Property(
 *             property="plate",
 *             description="Plate",
 *             type="string",
 *         ),
 *         @SWG\Property(
 *             property="created",
 *             description="Created date",
 *             type="string",
 *         ),
 * )
 */

/**
 * @SWG\Definition(
 *     definition="PlateWithMessages",
 *     @SWG\Property(
 *         property="plate",
 *         description="Plate",
 *         type="object",
 *         @SWG\Property(
 *             property="messages",
 *             description="Messages",
 *             type="array",
 *             @SWG\Items(ref="#/definitions/Message"),
 *         )
 *     )
 * )
 */

/**
 * @SWG\Definition(
 *     definition="Message",
 *         @SWG\Property(
 *             property="date",
 *             type="string",
 *         ),
 *         @SWG\Property(
 *             property="text",
 *             type="string",
 *         ),
 * )
 */