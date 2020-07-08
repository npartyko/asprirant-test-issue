<?php
/**
 * 2019-06-13.
 */

declare(strict_types=1);

namespace App\Provider;

use App\Auth\Auth;
use App\Controller\AuthController;
use App\Controller\HomeController;
use App\Controller\LikeController;
use App\Middleware\AuthMiddleware;
use App\Support\Config;
use App\Support\ServiceProviderInterface;
use App\Validation\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Container\ContainerInterface;
use Slim\Interfaces\RouteCollectorInterface;
use Slim\Interfaces\RouteCollectorProxyInterface;
use function Sodium\add;
use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use UltraLite\Container\Container;

/**
 * Class WebProvider.
 */
class WebProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed|void
     */
    public function register(Container $container)
    {
        $this->defineControllerDi($container);
        $this->defineRoutes($container);
    }

    /**
     * @param Container $container
     */
    protected function defineControllerDi(Container $container): void
    {
        $container->set(HomeController::class, static function (ContainerInterface $container) {
            return new HomeController(
                $container->get(RouteCollectorInterface::class),
                $container->get(Environment::class),
                $container->get(EntityManagerInterface::class),
                $container->get(Auth::class)
            );
        });

        $container->set(AuthController::class, static function (ContainerInterface $container) {
            return new AuthController(
                $container->get(RouteCollectorInterface::class),
                $container->get(Environment::class),
                $container->get(EntityManagerInterface::class),
                $container->get(Validator::class),
                $container->get(Auth::class)
            );
        });

        $container->set(LikeController::class, static function (ContainerInterface $container) {
            return new LikeController(
                $container->get(RouteCollectorInterface::class),
                $container->get(Environment::class),
                $container->get(EntityManagerInterface::class),
                $container->get(Validator::class),
                $container->get(Auth::class)
            );
        });
    }

    /**
     * @param Container $container
     */
    protected function defineRoutes(Container $container): void
    {
        $router = $container->get(RouteCollectorInterface::class);

        $router->group('/', function (RouteCollectorProxyInterface $router) use ($container) {
            $routes = self::getRoutes($container);
            foreach ($routes as $routeName => $routeConfig) {
                $q = $router->{$routeConfig['method']}($routeConfig['path'] ?? '', $routeConfig['controller'] . ':' . $routeConfig['action'])
                    ->setName($routeName);

                if (isset($routeConfig['middleware'])) {
                    $q->add(new $routeConfig['middleware']($container));
                }
            }
        });
    }

    /**
     * @param Container $container
     *
     * @return array
     */
    protected static function getRoutes(Container $container): array
    {
        return Yaml::parseFile($container->get(Config::class)->get('base_dir') . '/config/routes.yaml');
    }
}
