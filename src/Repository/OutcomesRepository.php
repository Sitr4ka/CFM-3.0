<?php

namespace App\Repository;

use App\Entity\Churches;
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
