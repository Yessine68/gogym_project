<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

    public function save(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Abonnement $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findById($id) : array
    {
        return $this->createQueryBuilder('a')
        ->where('a.id LIKE :id')
        ->setParameter('id',$id)
        ->getQuery()
        ->getResult();
    }

    public function findByNomOrType($query) : array
    {
        return $this->createQueryBuilder('a')
        ->andWhere('a.nom_a LIKE :searchNot OR a.type_a LIKE :searchNot')
        ->setParameter('searchNot', '%'.$query.'%')
        ->getQuery()
        ->getResult();
    }
    
    public function sortByAscPrix(): array
    {
        return $this->createQueryBuilder('a')
        ->orderBy('a.prix_a', 'ASC')
        ->getQuery()
        ->getResult();
    }
    
    public function sortByDescPrix(): array
    {
        return $this->createQueryBuilder('a')
        ->orderBy('a.prix_a', 'DESC')
        ->getQuery()
        ->getResult();
    }
    

//    /**
//     * @return Abonnement[] Returns an array of Abonnement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abonnement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
