<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth\Auth;
use App\Entity\User;
use App\Validation\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;
use Respect\Validation\Validator as v;

/**
 * Class AuthController.
 */
class AuthController
{
    /**
     * @var RouteCollectorInterface
     */
    private $routeCollector;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var Validator
     */
    private $validator;


    /**
     * @var Auth
     */
    private $auth;


    /**
     * AuthController constructor.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param Environment             $twig
     * @param EntityManagerInterface  $em
     * @param Validator  $validator
     * @param Auth  $auth
     */
    public function __construct(RouteCollectorInterface $routeCollector, Environment $twig, EntityManagerInterface $em, Validator $validator, Auth $auth)
    {
        $this->routeCollector = $routeCollector;
        $this->twig = $twig;
        $this->em = $em;
        $this->validator = $validator;
        $this->auth = $auth;

    }

    public function getSignUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->auth->check()) {
            return $response
                ->withHeader('Location',"/")
                ->withStatus(200);
        }

        $data = $this->twig->render('auth/signup.html.twig');

        $response->getBody()->write($data);

        return $response;
    }

    public function getSignIn(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        if ($this->auth->check()) {
            return $response
                ->withHeader('Location',"/")
                ->withStatus(200);
        }

        $data = $this->twig->render('auth/signin.html.twig');

        $response->getBody()->write($data);

        return $response;
    }


    public function signUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->validator->validate($request, [
            'username' => v::NotEmpty()->stringVal()->uniqueRule($this->em, User::class, 'username'),
            'password' => v::NotEmpty()->stringVal(),
        ]);

        $data = $request->getParsedBody();

        if ($this->validator->fail()) {

            $response->getBody()->write(json_encode($this->validator->getErrors()));
            return $response
                ->withHeader('Content-Type',"application/json;charset='utf-8")
                ->withStatus(422);
        }

        $user = (new User())
            ->setUsername($data['username'])
            ->setPassword($data['password']);

        $this->em->persist($user);
        $this->em->flush();

        $this->auth->loginById($user->getId());

        $response->getBody()->write(json_encode(['status' => true]));

        return $response
            ->withHeader('Content-Type',"application/json;charset='utf-8")
            ->withStatus(200);
    }

    public function signIn(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->validator->validate($request, [
            'username' => v::NotEmpty()->stringVal()->existsRule($this->em, User::class, 'username'),
            'password' => v::NotEmpty()->stringVal(),
        ]);

        $data = $request->getParsedBody();

        if ($this->validator->fail()) {

            $response->getBody()->write(json_encode($this->validator->getErrors()));
            return $response
                ->withHeader('Content-Type',"application/json;charset='utf-8")
                ->withStatus(422);
        }


        if (!$this->auth->attempt($data['username'], $data['password'])) {
            $response->getBody()->write(json_encode(['password' => 'Wrong password']));
            return $response
                ->withHeader('Content-Type',"application/json;charset='utf-8")
                ->withStatus(422);
        }

        $response->getBody()->write(json_encode(['status' => true]));

        return $response
            ->withHeader('Content-Type',"application/json;charset='utf-8")
            ->withStatus(200);
    }

    public function signOut(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $this->auth->logout();

        return $response
            ->withHeader('Location',"/")
            ->withStatus(200);
    }


}
