<?php

namespace App\Repository\Main;

use App\Entity\Main\TemplateContent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplateContent|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateContent|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateContent[]    findAll()
 * @method TemplateContent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateContentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateContent::class);
    }

    // /**
    //  * @return TemplateContent[] Returns an array of TemplateContent objects
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
    public function findOneBySomeField($value): ?TemplateContent
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
