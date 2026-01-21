<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ImageController extends AbstractController
{
    private string $imagesDirectory;

    public function __construct(string $projectDir)
    {
        $this->imagesDirectory = $projectDir . '/images';
    }

    #[Route('/img/home', name: 'img_home')]
    public function home(): Response
    {
        return $this->render('img/home.html.twig');
    }

    #[Route('/img/data/{reference}', name: 'img_data')]
    public function affiche(string $reference): Response
    {
        // Chercher l'image avec différentes extensions
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $imagePath = null;

        foreach ($extensions as $ext) {
            $path = $this->imagesDirectory . '/' . $reference . '.' . $ext;
            if (file_exists($path)) {
                $imagePath = $path;
                break;
            }
        }

        // Vérifier aussi si le fichier existe avec son nom complet
        if (!$imagePath) {
            $path = $this->imagesDirectory . '/' . $reference;
            if (file_exists($path) && !is_dir($path)) {
                $imagePath = $path;
            }
        }

        if (!$imagePath) {
            throw new NotFoundHttpException("L'image '$reference' n'existe pas.");
        }

        return $this->file($imagePath);
    }

    #[Route('/img/menu', name: 'img_menu')]
    public function menu(): Response
    {
        $images = [];

        if (is_dir($this->imagesDirectory)) {
            $files = scandir($this->imagesDirectory);
            foreach ($files as $file) {
                $fullPath = $this->imagesDirectory . '/' . $file;
                if (!is_dir($fullPath)) {
                    // Récupérer le nom sans extension pour la route
                    $pathInfo = pathinfo($file);
                    $images[] = [
                        'name' => $file,
                        'reference' => $pathInfo['filename'],
                    ];
                }
            }
        }

        return $this->render('img/menu.html.twig', [
            'images' => $images,
        ]);
    }
}
