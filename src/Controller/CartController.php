<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\LignePanier;
use App\Entity\Commande;
use App\Entity\LigneCommande;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/cart', name: 'cart')]
    public function index(SessionInterface $session): Response
    {
        // Récupérer le panier depuis la session
        $cart = $session->get('cart', []);

        // Calculer le total
        $total = 0;
        foreach ($cart as $item) {
            $quantity = $item['quantity'] ?? 1;
            $total += $item['price'] * $quantity;
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'total' => $total,
        ]);
    }

    #[Route('/cart/add/{name}/{price}', name: 'cart_add')]
    public function add(SessionInterface $session, EntityManagerInterface $em, $name, $price): Response
    {
        // 1. Ajouter à la session
        $cart = $session->get('cart', []);

        // Vérifier si le produit existe déjà
        $found = false;
        foreach ($cart as &$item) {
            if ($item['name'] === $name) {
                $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                $found = true;
                break;
            }
        }

        // Si non trouvé, ajouter nouveau
        if (!$found) {
            $cart[] = [
                'name' => $name,
                'price' => (float)$price,
                'quantity' => 1
            ];
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('home');
    }

    #[Route('/cart/remove/{index}', name: 'cart_remove')]
    public function remove(SessionInterface $session, $index): Response
    {
        $cart = $session->get('cart', []);
        if(isset($cart[$index])){
            unset($cart[$index]);
            $cart = array_values($cart);
            $session->set('cart', $cart);
        }

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/clear', name: 'cart_clear')]
    public function clear(SessionInterface $session): Response
    {
        $session->set('cart', []);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/checkout', name: 'cart_checkout')]
    public function checkout(SessionInterface $session, EntityManagerInterface $em): Response
    {
        // 1. Vérifications rapides
        if (!$this->getUser()) {
            $this->addFlash('error', 'Connectez-vous d\'abord.');
            return $this->redirectToRoute('app_login');
        }

        $cart = $session->get('cart', []);
        if (empty($cart)) {
            $this->addFlash('warning', 'Panier vide.');
            return $this->redirectToRoute('cart');
        }

        $client = $this->getUser();

        // 2. Créer commande
        $commande = new Commande();
        $commande->setClient($client);
        $commande->setDateCommande(new \DateTime());
        $commande->setStatus('en_attente');
        $commande->setModePaiement('à la livraison');
        $commande->setDateLivraison((new \DateTime())->modify('+1 day'));
        $commande->setAddresseLivraison($client->getAdresse() ?? '');

        $total = 0;

        // 3. Récupérer tous les produits en une requête (optimisation)
        $produitNoms = array_column($cart, 'name');
        $produits = $em->getRepository(Produit::class)->findBy(['nom' => $produitNoms]);

        // Index pour accès rapide
        $produitsIndex = [];
        foreach ($produits as $produit) {
            $produitsIndex[$produit->getNom()] = $produit;
        }

        // 4. Créer lignes de commande
        foreach ($cart as $item) {
            if (isset($produitsIndex[$item['name']])) {
                $produit = $produitsIndex[$item['name']];
                $quantite = $item['quantity'] ?? 1;

                $ligneCommande = new LigneCommande();
                $ligneCommande->setCommande($commande);
                $ligneCommande->setProduit($produit);
                $ligneCommande->setQuantite($quantite);
                $ligneCommande->setPrixUnitaire($item['price']);

                $total += $item['price'] * $quantite;

                $em->persist($ligneCommande);
                $commande->addLigneCommande($ligneCommande);
            }
        }

        // 5. Sauvegarder
        $commande->setMontantTotal((string)$total);
        $em->persist($commande);
        $em->flush();

        // 6. Vider panier et rediriger
        $session->set('cart', []);

        $this->addFlash('success', 'Commande #' . $commande->getId() . ' validée !');
        return $this->redirectToRoute('app_client_dashboard');
    }

    // Routes optionnelles pour gérer les quantités
    #[Route('/cart/increase/{name}/{price}', name: 'cart_increase')]
    public function increase(SessionInterface $session, $name, $price): Response
    {
        $cart = $session->get('cart', []);

        foreach ($cart as &$item) {
            if ($item['name'] === $name) {
                $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                break;
            }
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{name}', name: 'cart_decrease')]
    public function decrease(SessionInterface $session, $name): Response
    {
        $cart = $session->get('cart', []);

        foreach ($cart as $key => &$item) {
            if ($item['name'] === $name) {
                $currentQty = $item['quantity'] ?? 1;
                if ($currentQty > 1) {
                    $item['quantity'] = $currentQty - 1;
                } else {
                    // Si quantité = 1, supprimer l'article
                    unset($cart[$key]);
                    $cart = array_values($cart);
                }
                break;
            }
        }

        $session->set('cart', $cart);
        return $this->redirectToRoute('cart');
    }
}