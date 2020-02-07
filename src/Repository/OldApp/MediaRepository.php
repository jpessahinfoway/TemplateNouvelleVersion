<?php

namespace App\Repository\OldApp;

use App\Entity\OldApp\Image;
use App\Entity\OldApp\Media;
use App\Entity\OldApp\Video;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Media|null find($id, $lockMode = null, $lockVersion = null)
 * @method Media|null findOneBy(array $criteria, array $orderBy = null)
 * @method Media[]    findAll()
 * @method Media[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Media::class);
    }


    public function getElmtMediaArray()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.type = :type')
            ->setParameter('type', 'elmt')
            ->getQuery()
            ->getArrayResult()
        ;
    }




    /**
     * Return an array which will contain all medias which are used in infoway app
     * If media is not used by Image and Video entities, so its means that media is not used in app
     *
     * @return array
     */
    public function findAllMediaUsed(): array
    {
      $allMediaUser = [];

        function buildMediaArrayEntries($mediaObject){

          $objectNamespace = get_class($mediaObject);
          $parsedNamespace=explode('\\',$objectNamespace);
          $type = strtolower( $parsedNamespace[ count( $parsedNamespace ) -1 ] );
          $media = $mediaObject->getMedia();
          $filename = $media->getId().'.'.$mediaObject->getExtension();
          $path = '/medias/quick/'.$type.'s/low/'.$filename;

          if(file_exists('C:/inetpub/wwwroot/infowaytemplate/public'.$path)){
              return [
                'id'=> $media->getId(),
                'type' => $type,
                'name' => $media->getFilename(),
                'path' => $path,
                'elements' => [
                  'id' => $mediaObject->getId(),
                  'type' => $type,
                  'content' => $media->getId().'.'.$mediaObject->getExtension(),
                  'incrustOrder' => 0,
                ]
              ];
          }else return false;
        }
        $images = $this->getEntityManager()->getRepository(Image::class)->findAllTemplateImages();

        foreach($images as $image){
          $media = buildMediaArrayEntries($image);
          if( $media ) $allMediaUser[] = $media;
        }
        $videos = $this->getEntityManager()->getRepository(Video::class)->createQueryBuilder('v')->leftjoin('v.media','media')->where('media != :type')->setParameter('type','sync')->getQuery()->getResult();

        foreach($videos as $video){
          $media = buildMediaArrayEntries($video);
          if( $media ) $allMediaUser[] = $media;
        }



        // $allMediaUser = [];
        // $qb = $this->createQueryBuilder('m');
        // $qb->where('m.type != :mediatype')
        // ->setParameter('mediatype','sync');
        // $medias = $qb->getQuery()->getResult();
        //
        //
        // foreach ($medias as $media)
        // {
        //     $medias = ['images'=>[], 'videos'=>{}]
        //
        //     $image = $this->getEntityManager()->getRepository(Image::class)->findOneByMedia($media);
        //
        //     if(!$image)
        //     {
        //         $video = $this->getEntityManager()->getRepository(Video::class)->findOneByMedia($media);
        //
        //         //dd($video);
        //         if($video)
        //             $allMediaUser[] = [
        //                 'id' => $media->getId(),
        //                 'type' => 'video',
        //                 'name' => $media->getFilename(),
        //                 'elements' => [
        //                     [
        //                         'id' => $video->getId(),
        //                         'type' => 'video',
        //                         'content' => $media->getId() . '.' . $video->getExtension(),
        //                         //'content' => "67.png",
        //                         'incrustOrder' => 0
        //                     ]
        //                 ]
        //             ];
        //             //throw new NotFoundHttpException(sprintf("Error ! Media '%d' not found in Image and Video entities !", $media->getId()));
        //
        //     }
        //     else
        //         $allMediaUser[] = [
        //             'id' => $media->getId(),
        //             'type' => 'image',
        //             'name' => $media->getFilename(),
        //             'elements' => [
        //                 [
        //                     'id' => $image->getId(),
        //                     'type' => 'image',
        //                     'content' => $media->getId() . '.' . $image->getExtension(),
        //                     //'content' => "67.png",
        //                     'incrustOrder' => 0
        //                 ]
        //             ]
        //         ];
        //
        // }
        //

         return $allMediaUser;

    }

    // /**
    //  * @return Media[] Returns an array of Media objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Media
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
