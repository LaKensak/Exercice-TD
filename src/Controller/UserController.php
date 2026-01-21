<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/user/liste', name: 'app_user_liste')]
    public function liste(SessionInterface $session, UserRepository $userRepository): Response
    {
        if (!$session->get('logged_in')) {
            return $this->redirectToRoute('app_login');
        }

        $users = $userRepository->findAll();

        return $this->render('user/liste.html.twig', [
            'users' => $users,
        ]);
    }
}
