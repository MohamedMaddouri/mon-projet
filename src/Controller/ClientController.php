<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
class ClientController extends AbstractController
{
    #[Route('/client/dashboard', name: 'app_client_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('client/dashboard.html.twig', [
            'user' => $this->getUser(),
            // Add any necessary data for the client view here
        ]);
    }
}