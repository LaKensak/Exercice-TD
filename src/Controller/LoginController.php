<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class LoginController extends AbstractController
{
    #[Route('/connexion', name: 'app_login')]
    public function login(
        Request $request,
        SessionInterface $session,
        UserRepository $userRepository
    ): Response {
        if ($session->get('logged_in')) {
            return $this->redirectToRoute('app_home');
        }

        $user = new User();
        $form = $this->createForm(LoginType::class, $user);
        $form->handleRequest($request);

        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $dbUser = $userRepository->findByEmail($user->getEmail());

            if ($dbUser && password_verify($user->getPassword(), $dbUser->getPassword())) {
                $session->set('logged_in', true);
                $session->set('logged_email', $user->getEmail());
                $session->set('logged_user_id', $dbUser->getId());

                return $this->redirectToRoute('app_home');
            }

            $error = 'Email ou mot de passe incorrect.';
        }

        return $this->render('login/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    #[Route('/deconnexion', name: 'app_logout')]
    public function logout(SessionInterface $session): Response
    {
        $session->remove('logged_in');
        $session->remove('logged_email');
        $session->remove('logged_user_id');

        return $this->redirectToRoute('app_home');
    }
}
