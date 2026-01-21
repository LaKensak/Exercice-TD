<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ClientController
{
    #[Route('/client/prenom/{prenom}', name: 'client_info', requirements: ['prenom' => '[a-zA-Z]+(-[a-zA-Z]+)*'])]
    public function info(string $prenom): Response
    {
        // Simulation de données clients
        $clients = [
            'jean' => ['Jean Dupont', 'Jean Martin'],
            'jean-rene' => ['Jean-Rene Duval'],
            'marie' => ['Marie Curie', 'Marie Dupont'],
            'pierre' => ['Pierre Martin'],
        ];

        $prenomLower = strtolower($prenom);
        $noms = $clients[$prenomLower] ?? [];

        if (empty($noms)) {
            return new Response("Aucun client trouvé avec le prénom: $prenom");
        }

        return new Response("Clients avec le prénom '$prenom': " . implode(', ', $noms));
    }

    /**
     * Route ouverte seulement de 8h à 17h, sinon on exécute ferme()
     */
    #[Route('/client', name: 'client', options: ['ouverture' => '8-17'])]
    public function home(): Response
    {
        return new Response("Bonjour, bienvenue sur la page client !");
    }

    public function ferme(): Response
    {
        return new Response("Nous sommes fermés !");
    }
}
