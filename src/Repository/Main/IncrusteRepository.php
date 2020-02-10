<?php

namespace App\Repository\Main;

use App\Entity\Main\Incruste;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Incruste|null find($id, $lockMode = null, $lockVersion = null)
 * @method Incruste|null findOneBy(array $criteria, array $orderBy = null)
 * @method Incruste[]    findAll()
 * @method Incruste[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IncrusteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Incruste::class);
    }

    // /**
    //  * @return Incruste[] Returns an array of Incruste objects
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
    public function findOneBySomeField($value): ?Incruste
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
