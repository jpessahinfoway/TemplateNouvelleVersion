<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Video|null find($id, $lockMode = null, $lockVersion = null)
 * @method Video|null findOneBy(array $criteria, array $orderBy = null)
 * @method Video[]    findAll()
 * @method Video[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VideoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Video::class);
    }

    public function findVideosByMedias($mediaIds)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.media IN (:medias)')
            ->setParameter('medias', $mediaIds)
            ->getQuery()
            ->getResult()
            ;
    }
    public function findAllTemplateVideos()
    {
        return $this->createQueryBuilder('v')
            ->leftJoin('App\Entity\OldApp\TemplateContent', 'cont', 'WITH', 'v.media = cont.id')
            ->leftJoin('App\Entity\OldApp\Media', 'media', 'WITH', 'v.media = media.id')
            ->select('cont.id','v.extension','cont.name','media.type')
            ->andWhere('cont.id IS NOT NULL')
            ->andWhere('media.type = :type')
            ->setParameter("type","elmt")
            ->getQuery()
            ->getArrayResult()
            ;
    }


    // /**
    //  * @return Video[] Returns an array of Video objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Video
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
