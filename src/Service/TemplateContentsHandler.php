<?php


namespace App\Service;


use Doctrine\Persistence\ObjectRepository;
use App\Entity\OldApp\{
    TemplateContent as OldApp_TemplateContent,
    Image as OldApp_Image,
    Video as OldApp_Video,
    Media as OldApp_Media,
    TemplateText as OldApp_TemplateText,
    TemplateCssValue as OldApp_TemplateCssValue,
    TemplateCssProperty as OldApp_TemplateCssProperty,
    TemplateStyle as OldApp_TemplateStyle
    };
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;


class TemplateContentsHandler
{

    private $_manager;
    private $_templateContentRepository;
    private $_allTemplateContents;


    public function __construct($manager)
    {
        $this->_manager = $manager;
        $this->_templateContentRepository = $this->_manager->getRepository(OldApp_TemplateContent::class );
        $this->_mediaRepository = $this->_manager->getRepository(OldApp_Media::class );
        $this->_imageRepository = $this->_manager->getRepository(OldApp_Image::class );
        $this->_videoRepository = $this->_manager->getRepository(OldApp_Video::class );
        $this->_templateTextRepository = $this->_manager->getRepository(OldApp_TemplateText::class);
        $this->_templateCssValueRepository = $this->_manager->getRepository(OldApp_TemplateCssValue::class);
        $this->_templateCssPropertyRepository = $this->_manager->getRepository(OldApp_TemplateCssProperty::class);
        $this->_templateStyleRepository = $this->_manager->getRepository(OldApp_TemplateStyle::class);

    }

    public function getAllImages(){
        return $this->_imageRepository->findAllTemplateImages();
    }

    public function getAllVideos(){
        return $this->_videoRepository->findAllTemplateVideos();
    }

    public function getAllMedias(){
        return $this->_mediaRepository->getElmtMediaArray();
    }
    private  function convertMediasObjectsToMediasArrayWithExtension($mediasObjectList,$mediasId,$allMedias){

        $allMediasArray = [];

        foreach($mediasObjectList as $media){

            $mediaIdPosition =array_search($media->getMedia()->getId(),$mediasId);
            $allMediasArray[]=array_merge($allMedias[$mediaIdPosition],['extension'=>$media->getExtension()]);

        }
        return $allMediasArray;
    }

    public function getAllContentsFromDatabase(){
        $this->_allTemplateContents = $this->_templateContentRepository->findAllForCustomer();
    }

    public function returnAllMediasFromContents(){

        if(count($this->_allTemplateContents) <1) return [];

        return array_filter($this->_allTemplateContents,function($media){
            return $media['content_type']==='media';

        });
    }

    public function addPathToMediasArray($mediasArray,$pathToMedia){

       foreach($mediasArray as $index => $media){

           if(isset($media['id']) && isset($media['extension'])){
               $mediasArray[ $index ][] = $pathToMedia . $media['id'] . '.' . $media['extension'];
           }
       }

       return $mediasArray;
    }


    public function getAllImagesInSpecifedMedias($mediasList){

        $mediasId = array_column($mediasList,'id');

        $allImages = $this->_imageRepository->findImagesByMedias($mediasId);

        return $this->convertMediasObjectsToMediasArrayWithExtension($allImages,$mediasId,$mediasList);
    }


    public function getAllVideosInSpecifiedMedias($mediasList){

        $mediasId = array_column($mediasList,'id');

        $allVideos = $this->_videoRepository->findVideosByMedias($mediasId);

        return $this->convertMediasObjectsToMediasArrayWithExtension($allVideos,$mediasId,$mediasList);
    }


    public function getAllTextContents()
    {
        return $this->_templateTextRepository->findAll();
    }


    public function getAllTextContentStyles()
    {

        $styles = [];

        foreach ($this->_templateStyleRepository->findAll() as $item)
        {
            $styles[] = (object) [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'class' => $item->getClass()
            ];
        }

        return $styles;
    }




    public function setDatabaseManager(ObjectRepository $incrusteRepository): self
    {
        $this->incrusteRepository = $incrusteRepository;

        return $this;
    }

}