<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\TemplateCssProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplateCssProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateCssProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateCssProperty[]    findAll()
 * @method TemplateCssProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateCssPropertyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateCssProperty::class);
    }

    // /**
    //  * @return TemplateCssProperty[] Returns an array of TemplateCssProperty objects
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
    public function findOneBySomeField($value): ?TemplateCssProperty
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
