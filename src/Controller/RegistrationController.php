<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_registration')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserRepository $userRepository
    ): Response {
        $form = $this->createForm(RegistrationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $existingUser = $userRepository->findByEmail($data['email']);
            if ($existingUser) {
                $this->addFlash('error', 'Cet email est deja utilise.');
                return $this->render('registration/register.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            $user = new User();
            $user->setEmail($data['email']);
            $user->setPassword($data['password']);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_registration_success', [
                'email' => $data['email']
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/inscription/success', name: 'app_registration_success')]
    public function success(Request $request): Response
    {
        $email = $request->query->get('email');

        return $this->render('registration/success.html.twig', [
            'email' => $email,
        ]);
    }
}
