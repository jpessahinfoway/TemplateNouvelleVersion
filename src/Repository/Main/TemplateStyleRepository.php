<?php

namespace App\Repository\Main;

use App\Entity\Main\TemplateStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplateStyle|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateStyle|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateStyle[]    findAll()
 * @method TemplateStyle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateStyle::class);
    }

    // /**
    //  * @return TemplateStyle[] Returns an array of TemplateStyle objects
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
    public function findOneBySomeField($value): ?TemplateStyle
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
