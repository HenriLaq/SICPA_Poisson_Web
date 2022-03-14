<?php

namespace App\Repository;

use App\Entity\CourbePrevisionnelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CourbePrevisionnelle|null find($id, $lockMode = null, $lockVersion = null)
 * @method CourbePrevisionnelle|null findOneBy(array $criteria, array $orderBy = null)
 * @method CourbePrevisionnelle[]    findAll()
 * @method CourbePrevisionnelle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourbePrevisionnelleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CourbePrevisionnelle::class);
    }

    /**
     * @return CourbePrevisionnelle[] Returns an array of CourbePrevisionnelle objects, page Lot
     */
    public function findCourbeByLot($idLot)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.idLot = :val')
            ->setParameter('val', $idLot)
            ->getQuery()
            ->getResult();
    }

    /*
    public function findOneBySomeField($value): ?Lot
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
