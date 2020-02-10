<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\TemplateContent;
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

    public function findAllForCustomer()
    {
        return $this->createQueryBuilder('t')
            ->leftJoin('App\Entity\OldApp\Image', 'img', 'WITH', 't.id = img.media')
            ->leftJoin('App\Entity\OldApp\Media', 'media', 'WITH', 't.id = media.id')
            ->select('t.id,t.name,img.extension,media.type')
            ->getQuery()
            ->getArrayResult();
    }


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
