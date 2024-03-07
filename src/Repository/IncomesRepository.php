<?php

namespace App\Repository;

use App\Entity\Incomes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Incomes>
 *
 * @method Incomes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incomes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incomes[]    findAll()
 * @method Incomes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncomesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incomes::class);
    }

    //    /**
    //     * @return Incomes[] Returns an array of Incomes objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Incomes
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
