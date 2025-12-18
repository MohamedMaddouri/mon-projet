<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
class ClientController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(Request $request, SessionInterface $session): Response
    {
        $foods = [
            ['name' => 'Pizza', 'image' => 'pizza.jpg', 'price' => 8],
            ['name' => 'Burger', 'image' => 'burger.jpg', 'price' => 6],
            ['name' => 'Pasta', 'image' => 'pasta.jpg', 'price' => 7],
        ];

        $drinks = [
            ['name' => 'Coke', 'image' => 'coke.jpg', 'price' => 2],
            ['name' => 'Coffee', 'image' => 'coffee.jpg', 'price' => 3],
            ['name' => 'Tea', 'image' => 'tea.jpg', 'price' => 2.5],
            ['name' => 'Juice', 'image' => 'juice.jpg', 'price' => 3],
        ];

        // 🔍 Filtres
        $q = $request->query->get('q');
        $category = $request->query->get('category');
        $alphabetical = $request->query->get('alphabetical');
        $price = $request->query->get('price');

        $all = array_merge($foods, $drinks);

        if ($q) {
            $all = array_filter($all, fn($item) =>
            str_contains(strtolower($item['name']), strtolower($q))
            );
        }

        if ($category === 'food') {
            $all = array_filter($all, fn($item) => in_array($item, $foods, true));
        }

        if ($category === 'drink') {
            $all = array_filter($all, fn($item) => in_array($item, $drinks, true));
        }

        if ($alphabetical === 'asc') {
            usort($all, fn($a, $b) => $a['name'] <=> $b['name']);
        }

        if ($alphabetical === 'desc') {
            usort($all, fn($a, $b) => $b['name'] <=> $a['name']);
        }

        if ($price === 'low') {
            usort($all, fn($a, $b) => $a['price'] <=> $b['price']);
        }

        if ($price === 'high') {
            usort($all, fn($a, $b) => $b['price'] <=> $a['price']);
        }

        // 🛒 RÉCUPÉRER LE PANIER DE LA SESSION
        $cart = $session->get('cart', []);

        return $this->render('client/home.html.twig', [
            'foods' => $foods,
            'drinks' => $drinks,
            'results' => $all,
            'cart' => $cart, // ✅ TRÈS IMPORTANT
        ]);
    }

    #[Route('/client/dashboard', name: 'app_client_dashboard')]
    public function dashboard(EntityManagerInterface $em): Response
    {
        // Récupérer l'utilisateur connecté (le client)
        $client = $this->getUser();

        // Récupérer les commandes et réservations du client
        $commandes = $em->getRepository(\App\Entity\Commande::class)
            ->findBy(['client' => $client], ['dateCommande' => 'DESC']);

        $reservations = $em->getRepository(\App\Entity\Reservation::class)
            ->findBy(['client' => $client], ['dateReservation' => 'DESC']);

        return $this->render('client/dashboard.html.twig', [
            'client' => $client,
            'commandes' => $commandes,
            'reservations' => $reservations,
        ]);
    }



}
