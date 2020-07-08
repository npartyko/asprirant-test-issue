<?php

declare(strict_types=1);

namespace App\Controller;

use App\Auth\Auth;
use App\Entity\Movie;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;

/**
 * Class HomeController.
 */
class LikeController
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
     * LikeController constructor.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param Environment             $twig
     * @param EntityManagerInterface  $em
     */
    public function __construct(RouteCollectorInterface $routeCollector, Environment $twig, EntityManagerInterface $em, Validator $validator, Auth $auth)
    {
        $this->routeCollector = $routeCollector;
        $this->twig = $twig;
        $this->em = $em;
        $this->validator = $validator;
        $this->auth = $auth;
    }

   public function toggle(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
   {
       $this->validator->validate($request, [
           'id' => v::NotEmpty()->intVal()->existsRule($this->em, Movie::class, 'id'),
           'state' => v::NotEmpty()->stringVal(),
       ]);

       if ($this->validator->fail()) {
           $response->getBody()->write(json_encode($this->validator->getErrors()));
           return $response
               ->withHeader('Content-Type',"application/json;charset='utf-8")
               ->withStatus(422);
       }
       $data = $request->getParsedBody();

       $id = $data['id'];
       $state = filter_var($data['state'], FILTER_VALIDATE_BOOLEAN);

       $movie = $this->em->getRepository(Movie::class)->find($id);

       if ($state) {
           $this->auth->user()->getMovies()->add($movie);
       } else {
           $this->auth->user()->getMovies()->removeElement($movie);
       }
       $this->em->flush();

       $response->getBody()->write(json_encode(['status' => true]));

       return $response
           ->withHeader('Content-Type',"application/json;charset='utf-8")
           ->withStatus(200);

   }
}
