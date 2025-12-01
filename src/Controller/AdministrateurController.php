<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrateurController extends AbstractController
{
    #[Route('/Admin/dashboard', name: 'app_admin_dashboard')]
    public function dashboard(): Response
    {
        // The user object is guaranteed to be an Administrateur instance
        // or a Utilisateur with ROLE_ADMIN at this point.

        return $this->render('Admin/dashboard.html.twig', [
            'user' => $this->getUser(),
            // Add any necessary data for the admin view here
        ]);
    }

}