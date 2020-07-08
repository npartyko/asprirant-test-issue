<?php


declare(strict_types=1);

namespace App\Middleware;


use App\Auth\Auth;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use UltraLite\Container\Container;

class AuthMiddleware
{

    private $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $handler)
    {
        if (!$this->container->get(Auth::class)->check()) {

            $response = new Response();
            $response->getBody()->write(json_encode('unauthorized'));

            return $response
                ->withHeader('Content-Type',"application/json;charset='utf-8")
                ->withStatus(401);

        }

        $response = $handler->handle($request);
        $existingContent = (string)$response->getBody();
        $response = new Response();
        $response->getBody()->write($existingContent);


        return $response;
    }

}
