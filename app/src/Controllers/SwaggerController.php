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
