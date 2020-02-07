<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\CSSProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CSSProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method CSSProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method CSSProperty[]    findAll()
 * @method CSSProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CSSPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CSSProperty::class);
    }

    // /**
    //  * @return CSSProperty[] Returns an array of CSSProperty objects
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
    public function findOneBySomeField($value): ?CSSProperty
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
