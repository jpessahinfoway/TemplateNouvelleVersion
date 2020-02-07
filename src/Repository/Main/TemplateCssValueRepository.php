<?php

namespace App\Repository\Main;

use App\Entity\Main\TemplateCssValue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplateCssValue|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateCssValue|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateCssValue[]    findAll()
 * @method TemplateCssValue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateCssValueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateCssValue::class);
    }

    // /**
    //  * @return TemplateCssValue[] Returns an array of TemplateCssValue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TemplateCssValue
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
