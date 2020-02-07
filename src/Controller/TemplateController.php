<?php
namespace App\Controller;


use Doctrine\Persistence\ObjectManager;
use App\Entity\Main\{Incruste as Main_Incruste, Template as Main_Template, User as Main_User, Zone as Main_Zone,
                     IncrusteElement as Main_IncrusteElement, CSSProperty as Main_CssProperty, IncrusteStyle as Main_IncrusteStyle };

use App\Entity\OldApp\{Image as OldApp_Image,
    Incruste as OldApp_Incruste, TemplateStyle as OldApp_TemplateStyle, TemplateCssProperty as OldApp_TemplateCssProperty, TemplateCssValue as OldApp_TemplateCssValue,
    TemplateContent as OldApp_TemplateContent, Template as OldApp_Template, TemplateText as OldApp_TemplateText, Video as OldApp_Video,
    Zone as OldApp_Zone, IncrusteElement as OldApp_IncrusteElement, CSSProperty as OldApp_CssProperty, IncrusteStyle as OldApp_IncrusteStyle, Media as OldApp_Media,
    TemplatePrice as OldApp_TemplatePrice, ZoneContent as OldApp_ZoneContent, Background as OldApp_Background };

use Doctrine\ORM\EntityManager;

use \InvalidArgumentException;

use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;

use App\Repository\Main\{ CustomerRepository, UserRepository,
                          TemplateRepository as Main_TemplateRepository, IncrusteRepository as Main_IncrusteRepository };

use Symfony\Component\CssSelector\Exception\InternalErrorException;
use Symfony\Component\Yaml\Yaml;
use App\Repository\OldApp\{ TemplateRepository as OldApp_TemplateRepository, IncrusteRepository as OldApp_IncrusteRepository };

use App\Service\{CSSParser as CssParser,
    DatabaseAccessRegister, TemplateStyleHandler,
    ExternalFileManager,
    IncrusteCSSHandler,
    TemplateContentsHandler,
    IncrusteHandler,
    SessionManager};

use Symfony\Component\HttpKernel\Exception\{ NotFoundHttpException, AccessDeniedHttpException };

use Symfony\Component\HttpFoundation\{ Request, Response, JsonResponse };
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use\Exception;



class TemplateController extends AbstractController
{


    /**
     * @var SessionManager
     */
    private $sessionManager;
    /**
     * @var ExternalFileManager
     */
    private $externalFileManager;

    public function __construct(SessionManager $sessionManager, ExternalFileManager $externalFileManager)
    {

        $this->sessionManager = $sessionManager;
        $this->externalFileManager = $externalFileManager;

        if(!$this->userSessionIsInitialized())
            $this->initializeUserSession();

//        ob_clean();
//        ob_end_clean();
//
    }


    /**
     * Renvoie la page d'accueil du module template
     * @Route("/test", name="template::testimporttemplate" )
     * @throws \Exception
     */
    public function testImportTemplate()
    {
        $templates = $this->getDoctrine()
                          ->getRepository(Template::class)
                          ->getAllTemplateForCustomer(null);
        return new Response('ok');
    }


    /**
     * Renvoie la page d'accueil du module template
     * @Route("/", name="template::dfdsf")
     * @Route("/home/stage/{stage}/{token}", name="template::home", methods="GET",
     *     requirements = {"stage" : "1|2|3", "token": "[a-z0-9]{64}"},
     *     defaults={"token": null})
     *
     *
     * @param Request $request
     * @param string $stage
     * @param CustomerRepository $customerRepository
     * @param UserRepository $userRepository
     * @param ExternalFileManager $externalFileManager
     * @return Response
     * @throws \Exception
     */
    public function home(Request $request, int $stage, CustomerRepository $customerRepository, UserRepository $userRepository, ExternalFileManager $externalFileManager): Response
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        elseif ($request->get("token") !== null)
        {

            $user = $userRepository->findOneByToken($request->get('token'));
            if(!$user)
                throw new AccessDeniedHttpException("Access denied ! Cause : Token is not valid ! ");

            $this->updateUserSession($user, $request);
            //$this->createSymLinkToUserMedia();

            $user->setToken(null);

            $this->getDoctrine()->getManager()->flush();
            //$this->getDoctrine()->getManager()->clear();

            unset($user);

            //dd($this->sessionManager->get('user'));

            return $this->redirectToRoute('template::home', [
                'stage' => $stage,
                'token' => null
            ]);
        }


        elseif( is_null($request->get("token")) AND is_null($this->sessionManager->get('user')['QUICKNET']['token']) )
            throw new AccessDeniedHttpException("Access denied ! Cause : Token not found in URL and session ! ");

        if(!in_array($stage, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to access this page in this stage('%d') !", $stage));


        switch($stage){

            case 1 :
                $templatesToLoad = [
                    'create' => [] ,
                    'load'   => [1]
                ];
                break;

            case 2 :
                $templatesToLoad = [
                    'create' => [1] ,
                    'load'   => [2]
                ];
                break;

            case 3 :
                $templatesToLoad = [
                    'create' => [2] ,
                    'load'   => [3]
                ];
                break;

        }


        $allLevelsToLoad = array_unique (array_filter(array_merge($templatesToLoad['create'] , $templatesToLoad['load']), function($levelToLoad){
            return is_int($levelToLoad) && ($levelToLoad>=1 && $levelToLoad<=3) ;
        }) );

        $levelsToLoadByEnseigne = ['admin' => [],'enseigne' => []] ;

        foreach($allLevelsToLoad as $levelToLoad){
            if( $levelToLoad < 2 )$levelsToLoadByEnseigne['admin'][] = $levelToLoad;
            else $levelsToLoadByEnseigne['enseigne'][] = $levelToLoad ;
        }
        $templates = [];

        if( count( $levelsToLoadByEnseigne[ 'admin' ] ) > 0 ){
            $templates=array_merge($templates,$this->getDoctrine()
                                                   ->getManager('default')
                                                   ->getRepository(Main_Template::class )
                                                   ->findBy( ['level' => $levelsToLoadByEnseigne[ 'admin' ] ] ));

        }
        if(count( $levelsToLoadByEnseigne[ 'enseigne' ] ) > 0) {

            $templates = array_merge($templates,$this->getDoctrine()
                                                     ->getManager($this->sessionManager->get('user')['QUICKNET']['base'])
                                                     ->getRepository(OldApp_Template::class)
                                                     ->findBy(['level' => $levelsToLoadByEnseigne['enseigne']]));
        }


        $loadableTemplates = [];
        $creatableTemplates = [];
        foreach($templates as $template){
            $currentLevel = $template->getLevel();
            $currentOrientation = $template->getOrientation() ;
            if(in_array($currentLevel,$templatesToLoad['create']))$creatableTemplates[$currentOrientation][]=$template;
            if(in_array($currentLevel,$templatesToLoad['load']))$loadableTemplates[$currentOrientation][]=$template;
        }

        dump( $loadableTemplates, $creatableTemplates, $this->sessionManager->get('user'));

        return $this->render('template/accueil/accueil.html.twig', [
            'user'            => $this->sessionManager->get('user'),
            'controller_name'  => 'TemplateController',
            'loadableTemplates'   => $loadableTemplates,
            'creatableTemplates' => $creatableTemplates,
            'stage'              => $stage
        ]);
    }


    /**
     * Stage 1 (creation, modification, import)
     *
     * @Route(path="/template/stage/{stage}/{action}", name="template::stagesActions", requirements = {
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
    public function stagesActions(int $stage, string $action, Request $request): Response
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");


        if($action !== 'create' and $stage > 1 and !$this->checkIfDataExistInRequest($request, "template"))
            throw new \Exception("Missing 'template' parameter !");

        elseif ($action !== 'create' and $stage > 1 and !intval($request->request->get('template')))
            throw new \Exception("Bad 'template' parameter !");

        elseif(!$this->checkIfDataExistInRequest($request, "orientation"))
            throw new \Exception("Missing 'orientation' parameter !");

        elseif (strtoupper($request->request->get('orientation')) !== "H" AND strtoupper($request->request->get('orientation')) !== "V")
            throw new \Exception("Bad 'orientation' parameter !");


        //dd($action);

        if($action === 'create' and $stage < 2)
            return $this->createTemplate($request, $stage);

        else
            return $this->importTemplate($request, $stage, $action);

    }


    /**
     * @Route("/get/template/model", name="template::getTemplateModel", methods="POST")
     * @return Response
     */
    public function getTemplateModel(SessionManager $sessionManager): Response
    {

        if(!$sessionManager->get('user'))
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        $templates = $this->getDoctrine()->getRepository(Template::class)->findAll();

        $data = [];

        foreach ($templates as $template)
        {

            $zones = $this->getDoctrine()->getRepository(Zone::class)->findBy(['template' => $template]);

            foreach ($zones as $zone)
            {

                $data[$template->getName()]['template']['customer'] = $template->getCustomer();
                $data[$template->getName()]['template']['orientation'] = $template->getOrientation();
                $data[$template->getName()]['template']['background'] = $template->getBackground();
                $data[$template->getName()]['template']['size']['height'] = $template->getHeight();
                $data[$template->getName()]['template']['size']['width'] = $template->getWidth();

                $data[$template->getName()][$zone->getName()]['size']['height'] = $zone->getHeight();
                $data[$template->getName()][$zone->getName()]['size']['width'] = $zone->getWidth();

                $data[$template->getName()][$zone->getName()]['position']['top'] = $zone->getPositionTop();
                $data[$template->getName()][$zone->getName()]['position']['left'] = $zone->getPositionLeft();
                $data[$template->getName()][$zone->getName()]['position']['zIndex'] = $zone->getZIndex();

                $data[$template->getName()][$zone->getName()]['style']['class'] = strtolower($zone->getType()) . "-zone";

                //$data[$template->getName()][$zone->getName()]['contentId'] = $zone->getContentId();
                if($zone->getContent() !== NULL)
                {

                    foreach ($zone->getContent() as $contentId)
                    {

                        $incrustElement = $this->getDoctrine()->getManager('templateressources')->getRepository(IncrusteElement::class)->findOneBy(['id' => $contentId]);
                        $data[$template->getName()][$zone->getName()]['contents'][] = [
                            'id' => $contentId,
                            'incruste' => [
                                'id' => $incrustElement->getIncruste()->getId(),
                                'type' => $incrustElement->getIncruste()->getType(),
                                'name' => $incrustElement->getIncruste()->getName(),
                            ],
                            'parent' => [
                                'id' => ($incrustElement->getParent() !== NULL) ? $incrustElement->getParent()->getId() : null,
                            ],
                            'type' => $incrustElement->getType(),
                            'content' => $incrustElement->getContent(),
                            'class' => $incrustElement->getClass(),
                            'incrusteOrder' => $incrustElement->getIncrustOrder(),
                            'level' => $incrustElement->getLevel()
                        ];

                    }

                }


            }
        }

        //dump($data);die();

        return new JsonResponse($data);
    }


    /**
     * @Route(path="/get/all/stage/{stage}/template/name", name="template::getAllTemplateName", methods="POST",
     *     requirements={"stage": "1|2|3"})
     *
     * @param int $stage
     * @return Response
     */
    public function getAllTemplateName(int $stage): Response
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        if($stage === 1)
            $em = $this->getDoctrine()->getManager();

        else
            $em = $this->getDoctrine()->getManager($this->sessionManager->get('user')['QUICKNET']['base']);

        $data = [];

        foreach ($em->getRepository(($stage === 1) ? Main_Template::class : OldApp_Template::class)->findAll() as $template)
        {
            $data[] = $template->getName();
        }

        //$em->clear();

        return new JsonResponse($data);

    }


    /**
     * @Route(path="/get/{database}/template/{id}/data", name="template::getSpecificTemplateData",
     *     methods="POST", requirements={"database": "custom|default", "id": "\d+"})
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


        $orm = ($database === 'default') ? 'default': $this->sessionManager->get('user')['QUICKNET']['base'];


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

            $templateToJson['zones'][$index]['background'] = [];


            if($zone->getBackground() !== null)
            {

                if($zone->getBackground()->getContent() !== null)
                {

                    $exploded = explode("\\", get_class($zone->getBackground()->getContent()));

                    $type = strtolower($exploded[count($exploded)-1]);

                    $templateToJson['zones'][$index]['background'][] = [
                        'id' => $zone->getBackground()->getContent()->getId(),
                        'type' => $type,
                        'name' => $zone->getBackground()->getContent()->getFilename(),
                        'value' => $zone->getBackground()->getContent()->getId() . '.' . $zone->getBackground()->getContent()->getExtension(),
                        'extension' => $zone->getBackground()->getContent()->getExtension()
                    ];
                }

                if($zone->getBackground()->getStyle() !== null)
                {
                    $templateToJson['zones'][$index]['background'] = [
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

    private function getZoneContent($zone)
    {
        $exploded = explode("\\", get_class($zone->getContent()->getTemplateContent()));

        $type = strtolower($exploded[count($exploded)-1]);

        if(strpos($type, 'template') !== false)
            $type = str_replace('template', null, $type);

        $content = [
            'id' => $zone->getContent()->getTemplateContent()->getId(),
            'type' => $type,
            'name' => $zone->getContent()->getTemplateContent()->getName(),
            'value' => null,
            'style' => null,
            'contents' => []
        ];

        if($zone->getContent()->getTemplateStyle() !== null)
        {
            $content['style'] = $zone->getContent()->getTemplateStyle()->getClass();
        }

        if($type === 'text')
        {
            $content['value'] = $zone->getContent()->getTemplateContent()->getContent();
        }

        elseif($type === 'image')
        {
            $content['value'] = $zone->getContent()->getTemplateContent()->getId() . '.' . $zone->getContent()->getTemplateContent()->getExtension();
            $content['extension'] = $zone->getContent()->getTemplateContent()->getExtension();
        }

        foreach($zone->getContent()->getTemplateContent()->getChildren() as $children)
        {
            $content['sub_contents'][] = $this->getZoneContent($children);
        }


        return $content;
    }

    /**
     * Renvoie la page d'accueil du module template
     * @Route("template/api/{database}/", name="template::exportTemplate",
     *     requirements={ "database" : "custom|default"} )
     * @throws \Exception
     */
    public function displayAllTemplate(string $database, Request $request){

        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_PRETTY_PRINT);


        $orientation = $request->query->get('orientation') ;
        if( $orientation !== null && $orientation !== 'H' && $orientation !== 'V'){
            return $response->setData('error : invalid parameter for orientation');
        }

        $allManagers = array_keys($this->getDoctrine()->getManagers());

        if(!in_array($database,$allManagers)){
            return $response->setData('{error : Invalid database }') ;
        };

        $circularReferenceHandlingContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $encoder =  new JsonEncoder();
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $circularReferenceHandlingContext);
        $serializer = new Serializer( [ $normalizer ] , [ $encoder ] );


        $em = $this->getDoctrine()->getManager($database);
        $templateRepo = $em->getRepository( ($database === 'default') ? Main_Template::class : OldApp_Template::class);


        $findBy = ['level' => 1];
        if($orientation!== null )$findBy['orientation'] = $orientation;

        $templates =  $templateRepo->findBy($findBy);
        $templatesToJson = $serializer->serialize($templates, 'json');

        return $response->setData(json_decode($templatesToJson));
    }

    /**
     * Renvoie la page d'accueil du module template
     * @Route("template/api/{database}/{id}", name="template::exportTemplateById",
     *     requirements = {"id": "\d+", "database": "default|custom" }))
     * @throws \Exception
     */
    public function exportTemplateById(string $database, string $id){


        $response = new JsonResponse();
        $response->headers->set('Content-Type', 'application/json');
        $response->setEncodingOptions(JSON_PRETTY_PRINT);

        $database = ($database !== 'default') ? $this->sessionManager->get('user')['QUICKNET']['base'] : $database;

        $allManagers = array_keys($this->getDoctrine()->getManagers());

        if(!in_array($database,$allManagers)){
            return $response->setData('{error : Invalid database }') ;
        };

        $circularReferenceHandlingContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];

        $encoder =  new JsonEncoder();
        $normalizer = new ObjectNormalizer(null, null, null, null,
                                           null, null, $circularReferenceHandlingContext);

        $serializer = new Serializer( [ $normalizer ] , [ $encoder ] );

        $em = $this->getDoctrine()->getManager($database);
        $templateRepo = $em->getRepository(($database === 'default') ? Main_Template::class : OldApp_Template::class);
        //dd($database);

        $template =  $templateRepo->findOneBy(['id'=>$id]);

        //dd($template);

        $templatesToJson = $serializer->serialize($template, 'json');
        //dd(json_decode($templatesToJson));
        //$em->clear();

        return $response->setData(json_decode($templatesToJson));
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
                 ->setOrientation($templateToRegister->orientation)
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

            if( property_exists($zone, "zoneParent") AND !is_null($zone->zoneParent))
            {

                $zoneParent = $em->getRepository(get_class($newZone))->findOneBy(
                    ['name' => $template->getName() . "_" . $zone->zoneParent->name]);

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
     * @Route("/template/stage/{stage}/load/style/{id}", name="template::loadCss",methods="GET",
     *     requirements={"stage": "1|2|3"})
     *
     * @param int $stage
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function loadCss(int $stage, int $id, Request $request)
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        if($stage === 1)
            $em = $this->getDoctrine()->getManager();

        else
            $em = $this->getDoctrine()->getManager($this->sessionManager->get('user')['QUICKNET']['base']);


        $allStyles = $em->getRepository(OldApp_TemplateStyle::class)->findById($id);


        $templateStyleHandler = new TemplateStyleHandler($allStyles);

        $response = new Response($templateStyleHandler->generateCSS());
        $response->headers->set('Content-Type', 'text/css');

        //$em->clear();

        return $response;
    }

    /**
     * @Route("/testpage", name="template::testPage",methods="GET")
     */
    public function testPage(SerializerInterface $serialize){//, DatabaseAccessRegister$databaseAccessRegister)
        //$databaseAccessRegister->load();

        return new Response("hello world");

    }


    /**
     * @Route("/template/stage/{stage}/model/register", name="template::registerModel",
     *     methods="GET", requirements={"stage" : "1|2|3"})
     *
     * @param Request $request
     * @param int $stage
     * @return Response
     */
    public function registerModel(Request $request, int $stage){

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        elseif (!$this->sessionManager->get('user')['QUICKNET']['base'])
            throw new AccessDeniedHttpException("Access denied ! Cause : invalid session data !");

        dd(json_decode($request->get('incrusteStyle')));

        /*function registerIncrustElement($stage, $incrustElement, $incrustParent, $em, $parentIncrustElement=false){

            $type =  $incrustElement['_type'] ;
            $className = $type . $incrustParent->getId();


            $newIncrusteElement = ($stage === 1) ? new Main_IncrusteElement() : new OldApp_IncrusteElement();

            $incrusteContentStyle = ($stage === 1) ? new Main_IncrusteStyle() : new OldApp_IncrusteStyle();

            $incrustParent->addIncrusteElement($newIncrusteElement);

            $newIncrusteElement->setType($type);
            $newIncrusteElement->setContent($incrustElement['_content']);
            $newIncrusteElement->setClass($className);
            $newIncrusteElement->setLevel(1);

            $newIncrusteElement->setIncrustOrder($incrustElement['_incrustOrder']);
            if($parentIncrustElement)$parentIncrustElement->addChild($newIncrusteElement);

            foreach($incrustElement['_style'] as $incrusteStyle){


                if(isset($incrusteStyle['name']))
                {
                    //dump( $em->getRepository(CSSProperty::class));
                    $property = $em->getRepository( ($stage === 1) ? Main_CssProperty::class : OldApp_CssProperty::class )->findOneBy([
                        'name' => $incrusteStyle['name']
                    ]);


                    if($property !== NULL && $incrusteStyle['propertyWritting'] !== NULL){

                        $incrusteContentStyle->setProperty($property);
                        $incrusteContentStyle->setValue($incrusteStyle['propertyWritting']);
                        $newIncrusteElement->addIncrusteStyle($incrusteContentStyle);
                        //dump($incrusteContentStyle);

                        $em->persist($incrusteContentStyle);

                    }
                }

            }

            $incrusteStyleClass = ($stage === 1) ? Main_IncrusteStyle::class : OldApp_IncrusteStyle::class;

            if(isset($incrusteContentStyle) && $incrusteContentStyle instanceof $incrusteStyleClass){
                $em->persist($newIncrusteElement);

                foreach($incrustElement['_subContents'] as $subIncrustContent){
                    registerIncrustElement((int) $stage,$subIncrustContent,$incrustParent,$em,$newIncrusteElement);
                }
            }


        }

        function buildPropertiesArray($propertiesList,$object){
            $propertiesValuesArray = [];
            foreach($propertiesList as $property){
                $getter = 'get'.ucfirst($property);
                if(method_exists($object,$getter))$propertiesValuesArray[$property] = $object->$getter() ;
            }
            return $propertiesValuesArray;
        }*/

        $orm = ($stage === 1) ? 'default' : $this->sessionManager->get('user')['QUICKNET']['base'];

        $newIncruste = ($stage === 1) ? new Main_Incruste() : new OldApp_Incruste();

        $entityManager = $this->getDoctrine()->getManager($orm);

        $incrusteStyle = json_decode($request->get('incrusteStyle'),true);
        //dump($incrusteStyle);

        $incrusteResponse = [];

        $incrusteExist = $entityManager->getRepository(($stage === 1) ? Main_Incruste::class : OldApp_Incruste::class)->findOneBy([
            'name' => $incrusteStyle['_name'], 'type' => $incrusteStyle['_type']
        ]);

        if(!$incrusteExist)
        {

            $newIncruste->setName($incrusteStyle['_name'])
                        ->setType($incrusteStyle['_type']);

            $incrusteResponse['name'] = $newIncruste->getName();
            $incrusteResponse['type'] = $newIncruste->getType();
            $entityManager->persist($newIncruste);
            $entityManager->flush();

        }
        else
        {
            $newIncruste = $incrusteExist;
        }



        foreach($incrusteStyle['_incrusteElements'] as $content)
        {
            $this->registerIncrustElement($stage, $content,$newIncruste,$entityManager);
        }


        if(!$incrusteExist)
            $entityManager->persist($newIncruste);


        $entityManager->flush();
        //$entityManager->clear();


        if($newIncruste->getId() !== null)
        {
            $incrusteCreated = $this->buildPropertiesArray(['id', 'name', 'type'], $newIncruste);
            $incrusteCreated['incrusteElements'] = [];

            foreach ($newIncruste->getIncrusteElements() as $incrustElement)
            {
                if ($incrustElement->getId() !== null)
                {
                    $incrusteCreated['incrusteElements'][] = $this->buildPropertiesArray(['id', 'type', 'content', 'class','incrustOrder'], $incrustElement);
                }
            }
        }

        return new JsonResponse($incrusteCreated ?? []);
        //return new Response(json_encode($response));
    }


    /**
     * @Route(path="/register/content/type/{type}", name="template::registerContentByType", methods="POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function registerContentByType(Request $request, string $type): JsonResponse
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        elseif (!$this->sessionManager->get('user')['QUICKNET']['base'])
            throw new AccessDeniedHttpException("Access denied ! Cause : invalid session data !");

        $em = $this->getDoctrine()->getManager($this->sessionManager->get('user')['QUICKNET']['base']);

        if(!$em->getRepository(OldApp_TemplateText::class)->findOneByContent($request->request->get('content')))
        {

            $textContent = new OldApp_TemplateText();
            $textContent->setName($request->request->get('name'))
                        ->setContent($request->request->get('content'));

            $em->persist($textContent);
            $em->flush();



            //dd($textContent);

            return new JsonResponse([
                'id' => $textContent->getId(),
                'name' => $textContent->getName(),
                'content' => $textContent->getContent()
            ]);

        }

        return new JsonResponse([]);


    }


    /**
     * @Route(path="/register/content/style", name="template::registerContentStyle", methods="POST")
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function registerContentStyle(Request$request): Response
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        elseif (!$this->sessionManager->get('user')['QUICKNET']['base'])
            throw new AccessDeniedHttpException("Access denied ! Cause : invalid session data !");

        $styleReceived = json_decode($request->request->get('style'));

        if(!property_exists($styleReceived, 'css'))
        {
            throw new Exception(sprintf("Internal Error ! Style not have property '%s'", "css"));
        }

        $em = $this->getDoctrine()->getManager($this->sessionManager->get('user')['QUICKNET']['base']);

        $lastId = $em->getRepository(OldApp_TemplateStyle::class)->getLastInsertId();
        $lastId = ($lastId !== null) ? $lastId : '0';

        $templateStyle = new OldApp_TemplateStyle();

        $class = $styleReceived->title . '_' . $lastId;

        $templateStyle->setName($styleReceived->title)
                      ->setClass($class);

        foreach ($styleReceived->css as $property => $value)
        {

            $templateCssProperty = new OldApp_TemplateCssProperty();

            $templateCssValue = new OldApp_TemplateCssValue();

            $templateCssProperty->setName($property);

            $templateCssValue->setProperty($templateCssProperty)
                             ->setValue($value);

            $templateStyle->addCssValue($templateCssValue)
                          ->addProperty($templateCssProperty);

            $em->persist($templateStyle);
            $em->persist($templateCssProperty);
            $em->persist($templateCssValue);

        }

        $em->flush();

        //dd($styleReceived, $templateCssProperty, $templateStyle, $templateCssValue);

        return new Response("Enregistrement ok !");
    }


    private function buildPropertiesArray($propertiesList,$object)
    {

        $propertiesValuesArray = [];
        foreach($propertiesList as $property)
        {
            $getter = 'get'.ucfirst($property);
            if(method_exists($object,$getter))
                $propertiesValuesArray[$property] = $object->$getter() ;
        }
        return $propertiesValuesArray;
    }


    private function registerIncrustElement($stage, $incrustElement, &$incrustParent, $em, $parentIncrustElement=false)
    {

        $search = [
            'type' => $incrustElement['_type'],
            'class' => $incrustElement['_type'] . $incrustParent->getId(),
            'content' => $incrustElement['_content'],
            'level' => 1,
            'incrustOrder' => $incrustElement['_incrustOrder']
        ];

        $incrustElementExist = $em->getRepository( ($stage === 1) ? Main_IncrusteElement::class : OldApp_IncrusteElement::class )
                                  ->findOneBy($search);

        // don't duplicate media incruste lvl1 if exist
        if($incrustElementExist AND ($incrustElement['_type'] === 'image' OR $incrustElement['_type'] === 'video'))
        {
            return;
        }

        $type =  $incrustElement['_type'];
        $className = $type . $incrustParent->getId();


        $newIncrusteElement = ($stage === 1) ? new Main_IncrusteElement() : new OldApp_IncrusteElement();

        $incrustParent->addIncrusteElement($newIncrusteElement);

        $newIncrusteElement->setType($type);
        $newIncrusteElement->setContent($incrustElement['_content']);
        $newIncrusteElement->setClass($className);
        $newIncrusteElement->setLevel(1);

        $newIncrusteElement->setIncrustOrder($incrustElement['_incrustOrder']);
        if($parentIncrustElement)$parentIncrustElement->addChild($newIncrusteElement);

        foreach($incrustElement['_style'] as $incrusteStyle)
        {


            if(isset($incrusteStyle['name']))
            {
                //dump( $em->getRepository(CSSProperty::class));
                $property = $em->getRepository( ($stage === 1) ? Main_CssProperty::class : OldApp_CssProperty::class )->findOneBy([
                    'name' => $incrusteStyle['name']
                ]);

                //dump($property);

                if($property !== NULL && $incrusteStyle['propertyWritting'] !== '')
                {
                    $incrusteContentStyle = ($stage === 1) ? new Main_IncrusteStyle() : new OldApp_IncrusteStyle();
                    $incrusteContentStyle->setProperty($property);
                    $incrusteContentStyle->setValue($incrusteStyle['propertyWritting']);
                    $newIncrusteElement->addIncrusteStyle($incrusteContentStyle);

                    $property->addIncrusteStyle($incrusteContentStyle);

                    //dump($incrusteContentStyle);

                    $em->persist($incrusteContentStyle);
                    $em->flush();

                    //dump($incrusteContentStyle->getId());

                }
            }

        }

        //die();

        $incrusteStyleClass = ($stage === 1) ? Main_IncrusteStyle::class : OldApp_IncrusteStyle::class;

        if(isset($incrusteContentStyle) && $incrusteContentStyle instanceof $incrusteStyleClass)
        {
            $em->persist($newIncrusteElement);

            foreach($incrustElement['_subContents'] as $subIncrustContent)
            {
                $this->registerIncrustElement((int) $stage,$subIncrustContent,$incrustParent,$em,$newIncrusteElement);
            }
        }

    }



    /**
     * @Route("/template/stage2/getstyles", name="template::stage2getStyles",methods="POST")
     */
    public function stage2Open(SerializerInterface $serialize, ParameterBagInterface $parameterBag, CSSParser $CSSParser)
    {

        if(!$this->userSessionIsInitialized())
            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");

        $CSSContent = $CSSParser->parseCSS($parameterBag->get('kernel.project_dir').'/public/css/template/tools/zone_container_editor/text_styles/text-styles.css');

        $serializedCSS = $serialize->serialize($CSSContent,'json');

        return new Response($serializedCSS);

    }


    /**
     * @param Request $request
     * @param int $stageNumber
     * @return Response
     * @throws \Exception
     */
    private function createTemplate(Request $request, int $stageNumber = 1): Response
    {

        $incrusteRepository = $this->getDoctrine()->getManager()->getRepository( Main_Incruste::class );

        $incrusteHandler = new IncrusteHandler($incrusteRepository);
        $incrustData = $incrusteHandler->getIncrusteData();

        dump($this->sessionManager->get('user'));

        //$this->getDoctrine()->getManager()->clear();

        return $this->render('template/stages/index.html.twig', [
            'controller_name' =>    'TemplateController',
            'orientation'     =>    $request->request->get('orientation'),
            'stageNumber'     =>    $stageNumber,
            'action'          =>    'create',
            'classNames'      =>    $incrustData['classNames'],
            'ressources'      =>    $incrustData['ressources']
        ]);

    }


    /**
     * @param Request $request
     * @param int $stageNumber
     * @param string $action
     * @return Response
     */
    private function importTemplate(Request $request, int $stageNumber, string $action): Response
    {

        $templateId = $request->request->get('template');

        if( ($stageNumber === 2 and $action === "create") or ($action === "load" and $stageNumber === 1) )
            $orm = 'default';

        else
            $orm = $this->sessionManager->get('user')['QUICKNET']['base'];

        $manager = $this->getDoctrine()->getManager($orm);

        $templateFound = $manager->getRepository(($orm === 'default') ? Main_Template::class : OldApp_Template::class)
                                 ->findOneById( $templateId);

       // dd($orm, $action, $request, $sessionManager->get('user'));

        if(!$templateFound)
            throw new NotFoundHttpException(sprintf("Template '%d' not found !", $templateId));

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

        dump($this->sessionManager->get('user'), $allTextContents, $allTextStyles);

        return $this->render('template/stages/index.html.twig', [
            'controller_name' =>    'TemplateController',
            'template'        =>    $templateFound,
            'stageNumber'     =>    $stageNumber,
            'action'          =>    $action,
            'classNames'      =>    $incrustData['classNames'],
            'allImages'       =>    $allImages,
            'allTextStyles'       =>    $allTextStyles,
            'allTextContents'  => $allTextContents
        ]);
    }


    /**
     * @param object $background
     * @param Main_Zone | OldApp_Zone $zone
     * @param ObjectManager $entityManager
     * @throws Exception
     */
    private function registerZoneBackground(object $background, &$zone, ObjectManager &$entityManager)
    {

        if( !($zone instanceof Main_Zone) and !($zone instanceof OldApp_Zone) )
            throw new InvalidArgumentException("Error : 'zone' argument is not instance of 'Zone'");

        if($background->_color === null AND $background->_id === null)
            return;

        if($background->_color === null)
        {

            $background = $entityManager->getRepository(OldApp_Media::class)->findOneById($background->_id);

            if(!$background)
                throw new Exception(sprintf("Internal error : No media foudn where media.id = '%s'", $background->_id));

            if( ($zone->getBackground() !== null AND $zone->getBackground()->getId() !== $background->_id) OR ($zone->getBackground() === null) )
            {

                /*$newBackground = new OldApp_Media();
                $newBackground->setName($background->getName());*/

                $zone->setBackground($background);

            }

        }

        else
        {

            $templateCssProperty = $entityManager->getRepository(OldApp_TemplateCssProperty::class)->findOneByName('background-color');
            if(!$templateCssProperty)
            {
                $templateCssProperty = new OldApp_TemplateCssProperty();
                $templateCssProperty->setName("background-color");

                $entityManager->persist($templateCssProperty);
            }

            $templateCssValue = new OldApp_TemplateCssValue();
            $templateCssValue->setProperty($templateCssProperty)
                             ->setValue($background->_color);

            $lastId = $entityManager->getRepository(OldApp_TemplateStyle::class)->getLastInsertId();
            $lastId = ($lastId !== null) ? $lastId : '0';

            $templateStyle = new OldApp_TemplateStyle();
            $templateStyle->setName('background-color_' . $zone->getName())
                          ->setClass('background-color_' . $zone->getName() . '_' . $lastId)
                          ->addProperty($templateCssProperty)
                          ->addCssValue($templateCssValue);

            $background = new OldApp_Background();
            $background->setContent(null)
                       ->setStyle($templateStyle);

            $zone->setBackground($background);

            $entityManager->persist($background);
            $entityManager->persist($templateCssValue);
            $entityManager->persist($templateStyle);

        }

    }


    /**
     * @param array $zoneContent
     * @param Main_Zone | OldApp_Zone $zone
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Request $request
     * @throws Exception
     */
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

    private function isMedia(array $subject)
    {

        if($subject['type'] === 'image' OR $subject['type'] === 'video')
        {
            return true;
        }

        return false;

    }

    private function createMediaIncrusteIfNotExist(Request $request, array $incrusteData)
    {

        $values = [
            '_name' => 'mediaincruste1',
            '_type' => 'media',
            '_incrusteElements' => [
                $incrusteData['type'] => [
                    '_name' => null,
                    '_incrustOrder' => 0,
                    '_class' => null,
                    '_type' => $incrusteData['type'],
                    '_style' => [],
                    '_content' => $incrusteData['content'],
                    '_subContents' => []
                ]
            ]
        ];

        $request->request->set('incrusteStyle', json_encode($values));

        $insertResult = json_decode( ($this->registerModel($request, (int) $request->get('stage')))->getContent() );

        //dd($insertResult);
        return $insertResult;

    }


    /**
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Main_IncrusteElement | OldApp_IncrusteElement $incrusteElement
     * @param Main_Zone | OldApp_Zone $zone
     * @param int $level
     */
    private function duplicateIncrusteElement(ObjectManager &$entityManager, string $orm, $incrusteElement, &$zone, int $level = 2)
    {

        if( !($incrusteElement instanceof Main_IncrusteElement) and !($incrusteElement instanceof OldApp_IncrusteElement) )
            throw new InvalidArgumentException(sprintf("Internal Error ! Cause : 'incrusteElement' argument is not instance of 'IncrusteElement' !"));

        $search = [
            'level' => 2,
            'type' => $incrusteElement->getType(),
            'class' => $incrusteElement->getClass(),
            'content' => $incrusteElement->getContent(),
            'incrustOrder' => $incrusteElement->getIncrustOrder(),
            'zone' => $zone
        ];

        $incrusteElementExist= $entityManager->getRepository(($orm === "default") ? Main_IncrusteElement::class : OldApp_IncrusteElement::class)
                                             ->findOneBy($search);

        if(!$incrusteElementExist)
        {

            $newIncrusteElement = ($orm === "default") ? new Main_IncrusteElement() : new OldApp_IncrusteElement();
            $newIncrusteElement->setType($incrusteElement->getType())
                               ->setContent($incrusteElement->getContent())
                               ->setClass($incrusteElement->getClass())
                               ->setIncrustOrder($incrusteElement->getIncrustOrder())
                               ->setLevel($level)
                               ->setIncruste($incrusteElement->getIncruste());
                               //->setZone($zone);

            $zone->addIncrusteElement($newIncrusteElement);

            if($incrusteElement->getParent() !== null)
            {

                if($incrusteElement->getParent()->getLevel() === 1)
                    $this->duplicateIncrusteElementParent( $entityManager,  $orm, $newIncrusteElement, $incrusteElement->getParent(), $zone);

                else
                    $newIncrusteElement->setParent($incrusteElement->getParent());

            }

            else
                $newIncrusteElement->setParent(null);

            if($incrusteElement->getIncrusteStyles() !== null and $incrusteElement->getIncrusteStyles()->getValues() !== [])
            {
                $this->duplicateIncrusteElementStyles($entityManager,  $orm, $incrusteElement->getIncrusteStyles()->getValues(), $newIncrusteElement);
            }

            $entityManager->persist($newIncrusteElement);
            $entityManager->flush();

        }

    }

    /**
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Main_IncrusteElement | OldApp_IncrusteElement $incrusteElement
     * @param Main_IncrusteElement | OldApp_IncrusteElement $incrusteElementParent
     * @param Main_Zone | OldApp_Zone $zone
     * @param int $level
     */
    private function duplicateIncrusteElementParent(ObjectManager &$entityManager, string $orm, &$incrusteElement, $incrusteElementParent, &$zone, int $level = 2)
    {

        $search = [
            'level' => $level,
            'type' => $incrusteElementParent->getType(),
            'class' => $incrusteElementParent->getClass(),
            'content' => $incrusteElementParent->getContent(),
            'incrustOrder' => $incrusteElementParent->getIncrustOrder(),
            'zone' => $zone,
            'parent' => $incrusteElementParent->getParent(),
            'incruste' => $incrusteElementParent->getIncruste()
        ];

        //dd($search);

        $parentExist = $entityManager->getRepository(($orm === "default") ? Main_IncrusteElement::class : OldApp_IncrusteElement::class)
                                     ->findOneBy($search);

        if($parentExist)
            $incrusteElement->setParent($parentExist);

        else
        {

            $newParent = ($orm === "default") ? new Main_IncrusteElement() : new OldApp_IncrusteElement();
            $newParent->setType($incrusteElementParent->getType())
                      ->setContent($incrusteElementParent->getContent())
                      ->setClass($incrusteElementParent->getClass())
                      ->setParent($incrusteElementParent->getParent())
                      ->setIncrustOrder($incrusteElementParent->getIncrustOrder())
                      ->setLevel($level)
                      ->setIncruste($incrusteElementParent->getIncruste())
                      ->setZone($zone);

            if($incrusteElementParent->getIncrusteStyles() !== null)
            {
                $this->duplicateIncrusteElementStyles($entityManager,  $orm, $incrusteElementParent->getIncrusteStyles()->getValues(), $newParent);
            }

            $incrusteElement->setParent($newParent);
            $newParent->addChild($incrusteElement);

            $zone->addIncrusteElement($newParent);

            $entityManager->persist($newParent);

        }


    }

    /**
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Main_IncrusteStyle[] | OldApp_IncrusteStyle[] $incrusteStyles
     * @param Main_IncrusteElement | OldApp_IncrusteElement $incrusteElement
     */
    private function duplicateIncrusteElementStyles(ObjectManager &$entityManager, string $orm, $incrusteStyles, &$incrusteElement)
    {

        foreach ($incrusteStyles as $incrusteStyle)
        {

            $incrusteElementStyle = ($orm === "default") ? new Main_IncrusteStyle() : new OldApp_IncrusteStyle();
            $incrusteElementStyle->setIncrusteElement($incrusteElement)
                                 ->setProperty($incrusteStyle->getProperty())
                                 ->setValue($incrusteStyle->getValue());

            $incrusteElement->addIncrusteStyle($incrusteElementStyle);

            $entityManager->persist($incrusteElementStyle);

        }

    }


    /**
     * @param Main_Template | OldApp_Template $template
     * @param array $zones
     * @param ObjectManager $entityManager
     * @param string $orm
     * @param Request $request
     * @param int $stage
     * @return Response
     * @throws Exception
     */
    private function updateTemplate($template, array $zones, ObjectManager $entityManager, string $orm, Request $request)
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
            $zoneExist->setHeight($zone->size->height)
                    ->setWidth($zone->size->width)
                    ->setPositionTop($zone->position->top)
                    ->setPositionLeft($zone->position->left)
                    ->setIsBlocked($zone->isBlocked ?? false)
                    ->setZIndex($zone->zIndex);

            if($zoneExist)
            {
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

                if(!is_null($zone->zoneParent))
                {

                    $name = $template->getName() . "_" . $zone->zoneParent->name;
                    $zoneParent = $entityManager->getRepository($zoneObjectClass)->findOneByName($name);

                    $newZone->setParent($zoneParent);
                    $zoneParent->addChildren($newZone);

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


    private function removeUnnecessaryTemplateZones($template, array $zones, ObjectManager $entityManager, string $orm)
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

    /**
     * @param Request $request
     * @param $data
     * @return bool
     */
    private function checkIfDataExistInRequest(Request $request, $data): bool
    {

        if(!is_null($request->request->get($data)))
            return true;

        elseif (!is_null($request->get($data)))
            return true;

        return false;

    }

    private function userSessionIsInitialized()
    {
        return ( is_null($this->sessionManager->get('user')) ) ? false : true;
    }

    private function initializeUserSession()
    {
        $this->sessionManager->set('user', [
            'QUICKNET' => [
                'base'       =>     null,
                'token'      =>     null,
                'niveau'     =>     null,
                'RES_rep'    =>     null,
                'login'      =>     null,
            ],
            'new_app'       =>      false,
            'customer_id'   =>      null,
            'permissions'   =>      [ ]
        ]);
    }

    /**
     * @param MAIN_User $user
     * @param Request $request
     * @throws \Exception
     */
    private function updateUserSession(Main_User $user, Request $request)
    {

        $conf = Yaml::parse($this->externalFileManager->getFileContent($this->getParameter('project_dir') . "/../admin/config/parameters.yml"));

        // replace this by permissions !!!
        if($request->getHost() !== "127.0.0.1" and $request->getHost() !== "localhost")
            $stages = (explode('_', $user->getRole())[0] === "admin") ? [1, 2,3] : [3];

        // on local server
        // give all access
        else
            $stages = [1, 2, 3];


        $sessionData = [
            'QUICKNET' => [
                'base'       =>     $user->getDatabaseName(),
                'token'      =>     $request->get("token"),
                'niveau'     =>     explode('_', $user->getRole())[0],
                'login'      =>     $user->getLogin(),
            ],
            'new_app'        =>     strtolower(explode('_', $user->getRole())[0] === 'admin' OR strtolower($user->getDatabaseName()) === 'leclerc') ? true : false,
            'customer_id'    =>     $user->getIdLocal(),
            'permissions'    =>     [
                'access'     =>     $stages
            ]
        ];

        if($user->getDatabaseName() === 'quicknet')
            $sessionData['QUICKNET']['RES_rep'] = $conf['sys_path']['datas'] . '/data' . '/PLAYER INFOWAY WEB/';

        else
            $sessionData['QUICKNET']['RES_rep'] = $conf['sys_path']['datas'] . '/data_' . $user->getDatabaseName() . '/PLAYER INFOWAY WEB/';

        $this->sessionManager->replace('user', $sessionData);

    }


}
