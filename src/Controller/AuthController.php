<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;

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
     * AuthController constructor.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param Environment             $twig
     * @param EntityManagerInterface  $em
     */
    public function __construct(RouteCollectorInterface $routeCollector, Environment $twig, EntityManagerInterface $em)
    {
        $this->routeCollector = $routeCollector;
        $this->twig = $twig;
        $this->em = $em;
    }

    public function getSignUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $data = $this->twig->render('auth/signup.html.twig');

        $response->getBody()->write($data);

        return $response;
    }


    public function signUp(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $request = $request->getParsedBody();

        $data = $this->twig->render('auth/signup.html.twig');

        $response->getBody()->write($data);

        return $response;
    }


}
