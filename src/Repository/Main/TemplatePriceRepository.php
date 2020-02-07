<?php

namespace App\Repository\Main;

use App\Entity\Main\TemplatePrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplatePrice|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplatePrice|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplatePrice[]    findAll()
 * @method TemplatePrice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplatePriceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplatePrice::class);
    }

    // /**
    //  * @return TemplatePrice[] Returns an array of TemplatePrice objects
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
    public function findOneBySomeField($value): ?TemplatePrice
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
