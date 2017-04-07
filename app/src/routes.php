<?php

$app->group('/user', function () use ($app) {

    /**
     * @SWG\Post(
     *     path="/user/email/auth",
     *     tags={"User APIs"},
     *     summary="Initiate authentication - generate a unique code and send it to the given email address",
     *     @SWG\Parameter(
     *      name="email",
     *      in="body",
     *      description="User email address",
     *      required=true,
     *      type="string",
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
    $app->post('/email/auth', 'App\Controllers\UserController:initEmailAuth');

    /**
     * @SWG\Post(
     *     path="/user/email/confirm",
     *     tags={"User APIs"},
     *     summary="Authenticate existing user or create new user",
     *     @SWG\Parameter(
     *      name="code",
     *      in="body",
     *      description="Auth code sent to user",
     *      required=true,
     *      type="string",
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
    $app->post('/email/confirm', 'App\Controllers\UserController:confirmEmail');

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
     *      type="string",
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
    $app->post('/phone/auth', function ($request, $response, $args) {
        $this->logger->info("Slim-Skeleton '/' route");

        return json_encode([]);
    });

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
     *      type="string",
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
    $app->post('/phone/confirm', function ($request, $response, $args) {
        $this->logger->info("Slim-Skeleton '/' route");

        return json_encode([]);
    });


    /**
     * @SWG\Get(
     *     path="/user/plates",
     *     tags={"User APIs"},
     *     summary="Retrieve user plates",
     *     @SWG\Parameter(
     *      name="auth_code",
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
    $app->get('/plates', 'App\Controllers\UserController:getPlates');

    /**
     * @SWG\Post(
     *     path="/user/plate",
     *     tags={"User APIs"},
     *     summary="Add a new plate",
     *     @SWG\Parameter(
     *      name="code",
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
    $app->post('/plate', 'App\Controllers\UserController:addPlate');

    /**
     * @SWG\Delete(
     *     path="/user/plate",
     *     tags={"User APIs"},
     *     summary="Delete a plate",
     *     @SWG\Parameter(
     *      name="code",
     *      in="body",
     *      description="Auth code sent to user",
     *      required=true,
     *      type="string",
     *     ),
     *     @SWG\Parameter(
     *      name="plate",
     *      in="body",
     *      description="License plate to delete",
     *      required=true,
     *      type="string",
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
    $app->delete('/plate', 'App\Controllers\UserController:deletePlate');


});

/**
 * @SWG\Get(
 *     path="/plate/messages",
 *     tags={"Public APIs"},
 *     summary="Retrieve plate messages",
 *     @SWG\Parameter(
 *      name="plate",
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
$app->get('/plate/messages', function ($request, $response, $args) {
});

/**
 * @SWG\Post(
 *     path="/plate/messages",
 *     tags={"Public APIs"},
 *     summary="Post a plate message",
 *     @SWG\Parameter(
 *      name="plate",
 *      in="body",
 *      description="Plate you want the message to be sent to",
 *      required=true,
 *      type="string",
 *     ),
 *     @SWG\Parameter(
 *      name="text",
 *      in="body",
 *      description="Message text",
 *      required=true,
 *      type="string",
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
$app->post('/plate/messages', function ($request, $response, $args) {
});



$app->get('/', function ($request, $response, $args) {

    echo "Hello";

});


$app->get('/swagger', function ($request, $response, $args) {

    $swagger = \Swagger\scan(__DIR__);
    header('Content-Type: application/json');
    echo $swagger;

});


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