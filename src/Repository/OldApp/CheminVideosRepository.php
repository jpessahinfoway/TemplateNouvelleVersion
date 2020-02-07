<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\CheminVideos;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CheminVideos|null find($id, $lockMode = null, $lockVersion = null)
 * @method CheminVideos|null findOneBy(array $criteria, array $orderBy = null)
 * @method CheminVideos[]    findAll()
 * @method CheminVideos[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CheminVideosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CheminVideos::class);
    }

    // /**
    //  * @return CheminVideos[] Returns an array of CheminVideos objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CheminVideos
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
