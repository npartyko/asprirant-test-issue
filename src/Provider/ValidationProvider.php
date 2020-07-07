<?php

declare(strict_types=1);

namespace App\Provider;


use App\Support\ServiceProviderInterface;
use App\Validation\Rules\UniqueRule;
use App\Validation\Validator;
use Psr\Container\ContainerInterface;
use Respect\Validation\Factory;
use Respect\Validation\Validator as v;
use Slim\Interfaces\RouteCollectorInterface;
use UltraLite\Container\Container;

/**
 * Class ConsoleCommandProvider.
 */
class ValidationProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed|void
     */
    public function register(Container $container)
    {

        $container->set(Validator::class, static function(ContainerInterface $container) {
            return new Validator;
        });

        Factory::setDefaultInstance(
            (New Factory())
                ->withRuleNamespace('App\\Validation\\Rules')
                ->withExceptionNamespace('App\\Validation\\Exceptions')
        );

    }
}
