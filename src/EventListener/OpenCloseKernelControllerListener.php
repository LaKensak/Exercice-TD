<?php

namespace App\EventListener;

use App\Controller\ClientController;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\Routing\RouterInterface;

class OpenCloseKernelControllerListener
{
    public function __construct(
        private RouterInterface $router,
        private LoggerInterface $logger
    ) {
    }

    public function onKernelController(ControllerEvent $event): void
    {
        $this->logger->info('OpenCloseKernelControllerListener déclenché');

        // Récupérer le nom de la route depuis les attributs de la requête
        $request = $event->getRequest();
        $routeName = $request->attributes->get('_route');

        if (!$routeName) {
            return;
        }

        // Récupérer la route depuis le router
        $routeCollection = $this->router->getRouteCollection();
        $route = $routeCollection->get($routeName);

        if (!$route) {
            return;
        }

        // Vérifier si l'option "ouverture" est définie
        $ouverture = $route->getOption('ouverture');

        if (!$ouverture) {
            return;
        }

        $this->logger->info("Route '$routeName' a l'option ouverture: $ouverture");

        // Parser l'option ouverture (format "8-17")
        if (preg_match('/^(\d+)-(\d+)$/', $ouverture, $matches)) {
            $heureDebut = (int) $matches[1];
            $heureFin = (int) $matches[2];
            $heureActuelle = (int) date('G');

            $this->logger->info("Heure actuelle: $heureActuelle, Plage: $heureDebut-$heureFin");

            // Vérifier si on est dans la plage horaire
            if ($heureActuelle < $heureDebut || $heureActuelle >= $heureFin) {
                $this->logger->info("Site fermé - redirection vers ferme()");
                $event->setController([new ClientController(), 'ferme']);
            }
        }
    }
}
