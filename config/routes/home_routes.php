<?php



use App\Controller\HomeController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('app_home', '/')
        ->controller([HomeController::class, 'index']);
};
