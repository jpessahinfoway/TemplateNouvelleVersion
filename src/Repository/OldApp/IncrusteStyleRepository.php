<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\IncrusteStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IncrusteStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncrusteStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncrusteStyle[]    findAll()
 * @method IncrusteStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncrusteStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncrusteStyle::class);
    }

    // /**
    //  * @return IncrusteStyle[] Returns an array of IncrusteStyle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IncrusteStyle
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
