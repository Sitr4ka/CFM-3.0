<?php

namespace App\Repository;

use App\Entity\Churches;
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

    /**
     *  @return Incomes[] Returns an array of Incomes objects
     */
    public function findByMotif($input, Churches $church)
    {
        $queryBuilder = $this->createQueryBuilder('i')
        ->where('i.churches = :church')
        ->andWhere('i.motif LIKE :motif')
        ->setParameter('church', $church)
        ->setParameter('motif', '%'.$input['keyword'].'%')
        ->orderBy('i.executedAt', 'ASC')
        ->setMaxResults(10);

            if (($input['startDate']) && ($input['endDate'])) {
                $queryBuilder
                    ->andWhere('i.executedAt BETWEEN :startDate AND :endDate')
                    ->setParameter('startDate', $input['startDate'])
                    ->setParameter('endDate', $input['endDate']);
            }

        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
