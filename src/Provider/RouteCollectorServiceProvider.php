<?php

namespace Mailery\Template\Email\Provider;

use Psr\Container\ContainerInterface;
use Yiisoft\Di\Support\ServiceProvider;
use Yiisoft\Router\RouteCollectorInterface;
use Yiisoft\Router\Group;
use Yiisoft\Router\Route;
use Mailery\Template\Email\Controller\DefaultController;

final class RouteCollectorServiceProvider extends ServiceProvider
{
    public function register(ContainerInterface $container): void
    {
        /** @var RouteCollectorInterface $collector */
        $collector = $container->get(RouteCollectorInterface::class);

        $collector->addGroup(
            Group::create(
                '/brand/{brandId:\d+}',
                [
                    Route::methods(['GET', 'POST'], '/template/email/view/{id:\d+}', [DefaultController::class, 'view'])
                        ->name('/template/email/view'),
                    Route::methods(['GET', 'POST'], '/template/email/create', [DefaultController::class, 'create'])
                        ->name('/template/email/create'),
                    Route::methods(['GET', 'POST'], '/template/email/edit/{id:\d+}', [DefaultController::class, 'edit'])
                        ->name('/template/email/edit'),
                ]
            )
        );
    }
}
