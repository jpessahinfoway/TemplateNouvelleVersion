<?php

namespace App\Repository\Main;

use App\Entity\Main\TemplateText;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TemplateText|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateText|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateText[]    findAll()
 * @method TemplateText[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateTextRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateText::class);
    }

    // /**
    //  * @return TemplateText[] Returns an array of TemplateText objects
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
    public function findOneBySomeField($value): ?TemplateText
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
