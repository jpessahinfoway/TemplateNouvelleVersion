<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\ZoneContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ZoneContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method ZoneContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method ZoneContent[]    findAll()
 * @method ZoneContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ZoneContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZoneContent::class);
    }

    // /**
    //  * @return ZoneContent[] Returns an array of ZoneContent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('z.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ZoneContent
    {
        return $this->createQueryBuilder('z')
            ->andWhere('z.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
