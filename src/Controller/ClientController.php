<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Produit;

class ClientController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(
        Request $request,
        SessionInterface $session,
        ProduitRepository $produitRepo
    ): Response {

        // 1. Get Query Parameters
        $q = $request->query->get('q');
        $category = $request->query->get('category'); // Expected 'Food' or 'Drinks'
        $alphabetical = $request->query->get('alphabetical');
        $price = $request->query->get('price');

        // 2. Fetch Filtered Results from Database
        $results = $produitRepo->findFilteredProducts($q, $category, $alphabetical, $price);

        // 3. Fetch separate lists for the UI sections (optional)
        $foods = $produitRepo->findFilteredProducts(null, 'Food', null, null);
        $drinks = $produitRepo->findFilteredProducts(null, 'Drinks', null, null);

        // 🛒 CART SESSION
        $cart = $session->get('cart', []);

        return $this->render('client/home.html.twig', [
            'foods' => $foods,
            'drinks' => $drinks,
            'results' => $results,
            'cart' => $cart,
        ]);
    }
    #[Route('/product/{id}', name: 'product_show')]
    public function show(Produit $produit): Response
    {
        return $this->render('client/show.html.twig', [
            'produit' => $produit,
        ]);
    }
}