<?php


use App\Controller\ClientController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return function (RoutingConfigurator $routes): void {
    $routes->add('client_info', '/client/prenom/{prenom}')
        ->controller([ClientController::class, 'info'])
        ->requirements(['prenom' => '[a-zA-Z]+(-[a-zA-Z]+)*']);
};
