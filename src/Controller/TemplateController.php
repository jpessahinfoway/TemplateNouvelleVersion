<?php

namespace App\Controller;
use App\Entity\Main\Template;
use App\Entity\Main\Template as Main_Template;
use App\Entity\Main\Zone as Main_Zone;
use App\Entity\OldApp\Image as OldApp_Image;
use App\Entity\OldApp\Incruste as OldApp_Incruste;
use App\Entity\OldApp\Template as OldApp_Template;
use App\Entity\OldApp\TemplateContent as OldApp_TemplateContent;
use App\Entity\OldApp\TemplatePrice as OldApp_TemplatePrice;
use App\Entity\OldApp\TemplateStyle as OldApp_TemplateStyle;
use App\Entity\OldApp\TemplateText as OldApp_TemplateText;
use App\Entity\OldApp\Video as OldApp_Video;
use App\Entity\OldApp\Zone as OldApp_Zone;
use App\Entity\OldApp\ZoneContent as OldApp_ZoneContent;
use App\Entity\TemplateRessources\Incruste;
use App\Service\ExternalFileManager;
use App\Service\IncrusteHandler;
use App\Service\SessionManager;
use App\Service\TemplateContentsHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{

    private $sessionManager ;
    private $externalFileManager ;
    public function __construct(SessionManager $sessionManager, ExternalFileManager $externalFileManager)
    {

        $this->sessionManager = $sessionManager;
        $this->externalFileManager = $externalFileManager;

//        if(!$this->userSessionIsInitialized())
//            $this->initializeUserSession();

//        ob_clean();
//        ob_end_clean();
//
    }

    /**
     * Stage 1 (creation, modification, import)
     *
     * @Route(path="/template/stage/{stage}/{action}", name="template::launchStage", requirements = {
     *     "stage" : "1|2|3",
     *     "action": "create|load"
     *     }, methods="POST")
     *
     *
     * @param string $stage
     * @param string $action
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function launchStage(int $stage, string $action, Request $request): Response
    {


        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        $templateId = $request->get('template');
        $orientation = $request->get('orientation') ;

        $doctrineManager = null ;

        if( ( $action == 'load' && $stage === 1 ) || ( $action === 'create' && $stage === 2 ) ) $doctrineManager = $manager = $this->getDoctrine()->getManager('default');
        else  $doctrineManager = $manager = $this->getDoctrine()->getManager('quicknet');

        $template = null ;

         if( $action  !=='create' || $stage > 1 ) {
             if( $templateId === null )                           throw new \Exception("Missing 'template' parameter !");
             if( !intval( $templateId ) )                         throw new \Exception("Incorrect 'template' parameter !");
             if( $orientation !== 'H' && $orientation !== 'V')  throw new \Exception("Incorrect orientation parameter  !");

            $template = $this->importTemplate($templateId, $doctrineManager);
         }


         $manager = $this->getDoctrine()->getManager($this->sessionManager->get('user')['QUICKNET']['base']);

        $incrusteRepository = $manager->getRepository(OldApp_Incruste::class );
        $templateContentRepository = $manager->getRepository(OldApp_TemplateContent::class );


        $templateContentsHandler = new TemplateContentsHandler($manager);

        $allImages = $templateContentsHandler->getAllImages();
        $allVideos = $templateContentsHandler->getAllVideos();

        $allTextStyles = $templateContentsHandler->getAllTextContentStyles();
        $allTextContents = $templateContentsHandler->getAllTextContents();

        $incrusteHandler = new IncrusteHandler($incrusteRepository);

        $incrustData = $incrusteHandler->getIncrusteData();

        //$incrustData['classNames']['medias'] = $manager->getRepository(OldApp_Media::class)->findAllMediaUsed();

        //$manager->clear();

//        dd($this->sessionManager->get('user'), $allTextContents, $allTextStyles, $allImages);

//        dd($template);
        return $this->render('stages/index.html.twig', [
            'controller_name' =>    'TemplateController',
            'template'        =>    $template,
            'orientation'      => $orientation,
            'stage'     =>    $stage,
            'action'          =>    $action,
            'classNames'      =>    $incrustData['classNames'],
            'allImages'       =>    $allImages,
            'allTextStyles'       =>    $allTextStyles,
            'allTextContents'  => $allTextContents
        ]);
    }

    /**
     * @param Request $request
     * @param int $stageNumber
     * @param string $action
     * @return Response
     */
    private function importTemplate(int $templateId, EntityManager $doctrineManager)
    {



        $database =  $doctrineManager->getConnection()->getDatabase()  ;
        $templateFound = $doctrineManager->getRepository(($database === 'admin') ? Main_Template::class : OldApp_Template::class)
            ->findOneById( $templateId);

        // dd($orm, $action, $request, $sessionManager->get('user'));

        if(!$templateFound)
            throw new NotFoundHttpException(sprintf("Template '%d' not found !", $templateId));

        return $templateFound;
    }

    /**
     * @Route(path="/get/{database}/template/{id}/data", name="template::getSpecificTemplateData",
     *     methods="POST", requirements={"database": "custom|admin", "id": "\d+"})
     *
     * @param Request $request
     * @param string $database
     * @param int $id
     * @return Response
     */
    public function getSpecificTemplateData(Request $request, string $database, int $id): Response
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");


        $orm = ($database === 'admin') ? 'default': $this->sessionManager->get('user')['QUICKNET']['base'];


        $manager = $this->getDoctrine()->getManager($orm);

        $templateToJson = [];

        $template = $manager->getRepository(( $orm === 'default' ) ? Main_Template::class : OldApp_Template::class)->findOneById($id);

        if(!$template)
            return new Response("Not found !");

        $templateToJson['id'] = $template->getId();
        $templateToJson['name'] = $template->getName();
        $templateToJson['orientation'] = $template->getOrientation();
        $templateToJson['create_at'] = $template->getCreateAt();
        $templateToJson['modification_date'] = $template->getLastModificationDate();
        $templateToJson['height'] = $template->getHeight();
        $templateToJson['width'] = $template->getWidth();
        $templateToJson['level'] = $template->getLevel();
        $templateToJson['background'] = $template->getBackground();

        $templateToJson['zones'] = [];

        $zones = $template->getZones()->getValues();



        foreach ($zones as $index => $zone)
        {

            $templateToJson['zones'][$index]['id'] = (int) $zone->getId();
            $templateToJson['zones'][$index]['name'] = str_replace($template->getName() . '_', null, $zone->getName());
            $templateToJson['zones'][$index]['width'] = (int) $zone->getWidth();
            $templateToJson['zones'][$index]['height'] = (int) $zone->getHeight();

            $templateToJson['zones'][$index]['isBlocked'] = $zone->getIsBlocked();
            $templateToJson['zones'][$index]['background'] = $zone->getBackground();

            $templateToJson['zones'][$index]['zIndex'] = (int) $zone->getZIndex();

            $templateToJson['zones'][$index]['position'] = (int) $zone->getPosition();
            $templateToJson['zones'][$index]['positionTop'] = (int) $zone->getPositionTop();
            $templateToJson['zones'][$index]['positionLeft'] = (int) $zone->getPositionLeft();

            $templateToJson['zones'][$index]['type'] = $zone->getType();

            $templateToJson['zones'][$index]['parent'] = ($zone->getParent() !== null) ? $zone->getParent()->getId() : null;

            $templateToJson['zones'][$index]['background'] = [
                'color' => [],
                'content' => []
            ];


            if($zone->getBackground() !== null)
            {

                if($zone->getBackground()->getContent() !== null)
                {

                    $exploded = explode("\\", get_class($zone->getBackground()->getContent()));

                    $type = strtolower($exploded[count($exploded)-1]);

                    $templateToJson['zones'][$index]['background']['content'] = [
                        'id' => $zone->getBackground()->getContent()->getId(),
                        'type' => $type,
                        'name' => $zone->getBackground()->getContent()->getFilename(),
                        'value' => $zone->getBackground()->getContent()->getId() . '.' . $zone->getBackground()->getContent()->getExtension(),
                        'ext' => $zone->getBackground()->getContent()->getExtension()
                    ];
                }

                if($zone->getBackground()->getStyle() !== null)
                {
                    $templateToJson['zones'][$index]['background']['color'] = [
                        'id' => $zone->getBackground()->getStyle()->getId(),
                        'type' => 'background-color',
                        'value' => $zone->getBackground()->getStyle()->getCssValues()->getValues()[0]->getValue(),
                    ];

                }

            }


            if($zone->getContent() !== null)
            {
                $templateToJson['zones'][$index]['zone_content'] = $this->getZoneContent($zone);
            }

        }


        //dd($data['template']['zones'][3]);
        //$manager->clear();

        return new JsonResponse($templateToJson);

    }

    /**
     * @param Request $request
     * @param $data
     * @return bool
     */
    private function isDataExistInRequest(Request $request, $data): bool
    {
        return !is_null( $request-> request-> get( $data ) ) && !is_null( $request-> get( $data ) ) ;
    }

    private function userSessionIsInitialized()
    {
        return ( is_null($this->sessionManager->get('user')) ) ? false : true;
    }


    /**
     * @Route("/template/stage/{stage}/register", name="template::registerTemplate",
     *     methods="POST", requirements={"stage": "1|2|3"})
     *
     * @param Request $request
     * @param int $stage
     * @return Response
     * @throws Exception
     */
    public function registerTemplate(Request $request, int $stage)
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        if(!in_array($stage, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to register in this stage ('%d') !", $stage));

        elseif(!in_array(1, $this->sessionManager->get('user')['permissions']['access'])
            and !in_array(2, $this->sessionManager->get('user')['permissions']['access'])
            and !in_array(3, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException("Access denied ! Cause : user is not allowed to register in any stage !");


        $zonesToRegister = json_decode($request->request->get('zones'));
        $templateToRegister = json_decode($request->request->get('template'));


        //dd($zonesToRegister, $templateToRegister);

        $orm = ($stage === 1) ? 'default' : $this->sessionManager->get('user')['QUICKNET']['base'];

        $em = $this->getDoctrine()->getManager($orm);

        $templateObject = ($orm === "default") ? new Main_Template() : new OldApp_Template();

        $templateExist = $em->getRepository( get_class($templateObject))->findOneByName($templateToRegister->name);
        //dd($templateExist);
        if($templateExist)
            return $this->updateTemplate($templateExist, $zonesToRegister, $em, $orm, $request);


        $template = ($orm === "default") ? new Main_Template() : new OldApp_Template();
        $template->setName($templateToRegister->name)
            ->setBackground(1)
            ->setWidth($templateToRegister->attr->size->width)
            ->setHeight($templateToRegister->attr->size->height)
            ->setOrientation($templateToRegister->attr->orientation)
            ->setLastModificationDate(new \DateTime())
            ->setCreateAt(new \DateTime());

        //$this->getUser()->getCustomer()->addTemplate($template);

        if($stage === 1)
            $template->setLevel(1);

        elseif($stage === 2)
            $template->setLevel(2);

        else
            $template->setLevel(3);

        // insert zone
        foreach ($zonesToRegister as $index => $zone)
        {

            $newZone = ($orm === "default") ? new Main_Zone() : new OldApp_Zone();
            $newZone->setName($template->getName() . "_" . $zone->name)
                ->setType($zone->type)
                ->setTemplate($template)
                ->setHeight($zone->size->height)
                ->setWidth($zone->size->width)
                ->setPositionTop($zone->position->top)
                ->setPositionLeft($zone->position->left)
                ->setIsBlocked( $zone->isBlocked ?? false )
                //->setBackground(1)
                ->setPosition("absolute")
                ->setZIndex($zone->zIndex);

            if(property_exists($zone, "_background") && $zone->_background !== null)
            {
                $this->registerZoneBackground($zone->_background, $newZone, $em);
            }

            $template->addZones($newZone);
            $newZone->setTemplate($template);

            if( property_exists($zone, "parent") && !is_null($zone->parent))
            {

                $zoneParent = $em->getRepository(get_class($newZone))->findOneBy(
                    ['name' => $template->getName() . "_" . $zone->parent->name]);

                $newZone->setParent($zoneParent);
                $zoneParent->addChildren($newZone);

            }

            if(property_exists($zone, "_content") && $zone->_content !== null)
            {
                $this->registerZoneContent($zone->_content, $newZone, $em, $orm, $request);
            }


            if(!$em->contains($template))
                $em->persist($template);

            $em->persist($newZone);
            $em->flush();

        }

        //$em->clear();

        return new JsonResponse([
            'id' => $template->getId(),
            'name' => $template->getName(),
            'orientation' => $template->getOrientation(),
            'modification_date' => $template->getLastModificationDate()
        ]);

    }

    /**
     * @param Main_Template | OldApp_Template $template
     * @param array $zones
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    private function updateTemplate($template, array $zones, EntityManager $entityManager, string $orm, Request $request)
    {

        if(!($template instanceof Main_Template) and !($template instanceof OldApp_Template))
            throw new InvalidArgumentException("Error : 'template' argument is not instance of 'Template'");

        /** update template **/
        $template->setLastModificationDate(new \DateTime());

        //dd($template->getId(), $orm);

        $zoneObjectClass = ($orm === 'default') ?   Main_Zone::class :  OldApp_Zone::class;

        if(sizeof($template->getZones()->getValues()) !== sizeof($zones))
            $this->removeUnnecessaryTemplateZones($template, $zones, $entityManager, $orm);

        //dd($zones);

        /** update template zones **/
        // will contain zone parent name
        $zoneParentArray = [];
        //dump("all zones: ",$zones);
        // insert zone
        foreach ($zones as $zone)
        {

            $zoneExist = $entityManager->getRepository($zoneObjectClass)->findOneByName($template->getName() . "_" . $zone->name);

            if($zoneExist)
            {

                $zoneExist->setHeight($zone->size->height)
                    ->setWidth($zone->size->width)
                    ->setPositionTop($zone->position->top)
                    ->setPositionLeft($zone->position->left)
                    ->setIsBlocked($zone->isBlocked ?? false)
                    ->setZIndex($zone->zIndex);

                if(property_exists($zone, "_content") && $zone->_content !== null)
                {
                    $this->registerZoneContent($zone->_content, $zoneExist, $entityManager, $orm, $request);
                }

                if(property_exists($zone, "_background") AND $zone->_background !== null)
                {
                    $this->registerZoneBackground($zone->_background, $zoneExist, $entityManager);
                }

            }

            else
            {

                $newZone = ($orm === 'default') ? new  Main_Zone() : new OldApp_Zone();;
                $newZone->setName($template->getName() . "_" . $zone->name)
                    //->setBackground(1)
                    ->setType($zone->type)
                    ->setIsBlocked($zone->isBlocked ?? false)
                    ->setWidth($zone->size->width)
                    ->setHeight($zone->size->height)
                    ->setPositionTop($zone->position->top)
                    ->setPositionLeft($zone->position->left)
                    ->setZIndex($zone->zIndex)
                    ->setPosition("absolute")
                    ->setTemplate($template);

                if(property_exists($zone, "_background") && $zone->_background !== null)
                {
                    $this->registerZoneBackground($zone->_background->_id, $newZone, $entityManager);
                }

                $template->addZones($newZone);

                if(!is_null($zone->parent))
                {

                    $name = $template->getName() . "_" . $zone->parent->name;
                    $parentZone = $entityManager->getRepository($zoneObjectClass)->findOneByName($name);

                    $newZone->setParent($parentZone);
                    $parentZone->addChildren($newZone);

                }

                $entityManager->persist($newZone);

                if(property_exists($zone, "_content") && $zone->_content !== null)
                {
                    $entityManager->flush();
                    $this->registerZoneContent($zone->_content, $newZone, $entityManager, $orm, $request);
                }

            }

            $entityManager->flush();

        }

        //$entityManager->clear();

        return new JsonResponse([
            'id' => $template->getId(),
            'name' => $template->getName(),
            'orientation' => $template->getOrientation(),
            'modification_date' => $template->getLastModificationDate()
        ]);

    }

    private function registerZoneContent($zoneContent, &$zone, ObjectManager &$entityManager, string $orm, Request $request)
    {

        if($zone->getContent() === null OR $zone->getContent()->getTemplateContent()->getId() !== $zoneContent->_id)
        {

            if( !($zone instanceof Main_Zone) and !($zone instanceof OldApp_Zone) )
                throw new InvalidArgumentException("Error : 'zone' argument is not instance of 'Zone'");

            $orm = ($orm === 'default') ? $orm : $this->sessionManager->get('user')['QUICKNET']['base'];

            if($zoneContent->_type ==='image')
            {
                $media = $entityManager->getRepository( OldApp_Image::class )->findOneById($zoneContent->_id);
                $type ='image';
            }

            elseif($zoneContent->_type ==='video')
            {
                $media = $entityManager->getRepository( OldApp_Video::class )->findOneById($zoneContent->_id);
                $type ='video';
            }

            elseif($zoneContent->_type ==='text')
            {
                $media = $entityManager->getRepository( OldApp_TemplateText::class )->findOneById($zoneContent->_id);
                $type ='text';
            }

            else
            {
                $media = $entityManager->getRepository( OldApp_TemplatePrice::class )->findOneById($zoneContent->_id);
                $type ='price';
            }

            if(!$media)
                throw new Exception(sprintf("Internal error : No media found where media.id = '%s AND media.type = '%s'", $zoneContent->_id, $type));


            $newContent = new OldApp_ZoneContent();
            $newContent->setTemplateContent($media);

            if($zoneContent->_class !== null AND $zoneContent->_class !== '')
            {
                $style = $entityManager->getRepository(OldApp_TemplateStyle::class)->findOneByClass($zoneContent->_class);

                if (!$style)
                    throw new Exception(sprintf("Internal error : No style found where style.class = '%s'", $zoneContent->_class));

                $newContent->setTemplateStyle($style);

            }

            $zone->setContent($newContent);
            $entityManager->persist($newContent);
            //$entityManager->flush();

        }

    }

    private function removeUnnecessaryTemplateZones($template, array $zones, EntityManager $entityManager, string $orm)
    {

        if(!($template instanceof Main_Template) and !($template instanceof OldApp_Template))
            throw new InvalidArgumentException("Error : 'template' argument is not instance of Template");

        foreach ($zones as $index => $zone)
        {
            $zones[$index] = $zone->name;
        }

        $templateObject = ($orm === 'default') ? new Main_Template() : new OldApp_Template();
        $zoneObject = ($orm === 'default') ? new Main_Zone() : new OldApp_Zone();

        $templateZones = $entityManager->getRepository(get_class($templateObject))->getTemplateZonesName($template);

        foreach ($templateZones as $templateZone)
        {

            if(!in_array($templateZone, $zones))
            {
                $zoneToRemove = $entityManager->getRepository(get_class($zoneObject))->findOneByName($template->getName() . "_" . $templateZone);
                $template->removeZones($zoneToRemove);
                $entityManager->remove($zoneToRemove);
                //$entityManager->flush();
            }

        }

        //$entityManager->clear();

    }

}
