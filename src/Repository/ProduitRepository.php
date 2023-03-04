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

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findProd(string $search = null): array
    {
            return $this->createQueryBuilder('produit')
            ->andWhere('produit.nom_Prod LIKE :searchTerm')
            ->setParameter('searchTerm', '%'.$search.'%')
            ->getQuery()
            ->execute();
    }
    public function triProdByCat(int $id_cat = null): array
    {
            return $this->createQueryBuilder('produit')
            ->leftJoin('produit.categorie', 'categorie')
            ->andWhere('categorie.id = :searchTerm')
            ->setParameter('searchTerm', $id_cat)
            ->getQuery()
            ->execute();

    }
    public function getStat(): array
    {
            return $this->createQueryBuilder('produit')
            ->select('count(produit.nom_Prod) as nbre,categorie.nom')
            ->leftJoin('produit.categorie', 'categorie')
            ->groupBy("produit.categorie")
            ->getQuery()
            ->execute();

    }
    public function getProdsforPag()
    {
            return $this->createQueryBuilder('produit');
            
    }

//    /**
//     * @return Produit[] Returns an array of Produit objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Produit
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
