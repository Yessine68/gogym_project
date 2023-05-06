<?php

namespace App\Repository;

use App\Entity\Abonnementsalle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnementsalle>
 *
 * @method Abonnementsalle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnementsalle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnementsalle[]    findAll()
 * @method Abonnementsalle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementsalleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnementsalle::class);
    }

    public function save(Abonnementsalle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Abonnementsalle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}
