<?php
// src/Controller/SecurityController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // 1. Uncomment this block to redirect users who are already logged in
        if ($this->getUser()) {
            // Use the same logic as your AppCustomAuthenticator for redirection:
            $user = $this->getUser();

            if (in_array("ROLE_ADMIN", $user->getRoles(), strict: true)) {
                // Adjust 'app_admin_dashboard' to your actual Admin route name
                return $this->redirectToRoute('app_admin_dashboard');
            }

            // Default redirect for any other roles/users
            return $this->redirectToRoute('app_client_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Corrected the exception type to align with common Symfony skeleton practice
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}