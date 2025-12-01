<?php
// src/Controller/RegistrationController.php

namespace App\Controller;

use App\Entity\Client;
use App\Form\ClientRegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        // 1. Create a new instance of the Client entity
        $user = new Client();

        // 2. Create the form, binding it to the Client entity
        $form = $this->createForm(ClientRegistrationFormType::class, $user);
        $form->handleRequest($request);

        // 3. Process the form submission
        if ($form->isSubmitted() && $form->isValid()) {

            // Hash the password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Set the default role for a new registration
            // The Client entity should inherently have ROLE_CLIENT, but we set it explicitly here.
            $user->setRoles(['ROLE_CLIENT']);

            // Save the new Client entity to the database
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirect the new user to the login page or client dashboard
            // If you want to automatically log them in, you need more services injected.
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}