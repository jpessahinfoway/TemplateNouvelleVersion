<?php

namespace App\Repository\Main;

use App\Entity\Main\IncrusteElement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method IncrusteElement|null find($id, $lockMode = null, $lockVersion = null)
 * @method IncrusteElement|null findOneBy(array $criteria, array $orderBy = null)
 * @method IncrusteElement[]    findAll()
 * @method IncrusteElement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncrusteElementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IncrusteElement::class);
    }

    // /**
    //  * @return IncrusteElement[] Returns an array of IncrusteElement objects
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
    public function findOneBySomeField($value): ?IncrusteElement
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
