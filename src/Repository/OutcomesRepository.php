<?php

namespace App\Repository;

use App\Entity\Outcomes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Outcomes>
 *
 * @method Outcomes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Outcomes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Outcomes[]    findAll()
 * @method Outcomes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OutcomesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Outcomes::class);
    }

    //    /**
    //     * @return Outcomes[] Returns an array of Outcomes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Outcomes
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
