<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * Fetch products based on search, category, and sorting preferences.
     *
     * @param string|null $search The search query for the product name
     * @param string|null $categoryName The name of the category (e.g., 'Drinks' or 'Food')
     * @param string|null $sortAlpha Sorting order for name ('asc' or 'desc')
     * @param string|null $sortPrice Sorting order for price ('low' or 'high')
     * @return Produit[]
     */
    public function findFilteredProducts(?string $search, ?string $categoryName, ?string $sortAlpha, ?string $sortPrice): array
    {
        $qb = $this->createQueryBuilder('p')
            ->join('p.category', 'c')
            ->addSelect('c');

        if ($search) {
            $qb->andWhere('p.nom LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($categoryName) {
            // We use LIKE to be less strict with caps/plurals
            $qb->andWhere('c.nom LIKE :catName')
                ->setParameter('catName', '%' . $categoryName . '%');
        }

        // --- SORTING LOGIC FIX ---
        // If user picks a price sort, make it the FIRST priority
        if ($sortPrice === 'low') {
            $qb->orderBy('p.prix', 'ASC');
        } elseif ($sortPrice === 'high') {
            $qb->orderBy('p.prix', 'DESC');
        }

        // If user picks alpha sort, add it.
        // If price was already set, this becomes the second priority.
        if ($sortAlpha === 'asc') {
            if ($sortPrice) { $qb->addOrderBy('p.nom', 'ASC'); }
            else { $qb->orderBy('p.nom', 'ASC'); }
        } elseif ($sortAlpha === 'desc') {
            if ($sortPrice) { $qb->addOrderBy('p.nom', 'DESC'); }
            else { $qb->orderBy('p.nom', 'DESC'); }
        }

        return $qb->getQuery()->getResult();
    }

    // You can keep or remove the commented methods below depending on your needs
}