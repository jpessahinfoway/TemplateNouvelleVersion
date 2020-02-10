<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;use App\Entity\Main\{Incruste as Main_Incruste, Template as Main_Template, User as Main_User, Zone as Main_Zone,
    IncrusteElement as Main_IncrusteElement, CSSProperty as Main_CssProperty, IncrusteStyle as Main_IncrusteStyle };

use App\Entity\OldApp\{Image,
    Incruste as OldApp_Incruste, TemplateStyle as OldApp_TemplateStyle, TemplateCssProperty as OldApp_TemplateCssProperty, TemplateCssValue as OldApp_TemplateCssValue,
    TemplateContent as OldApp_TemplateContent, Template as OldApp_Template, TemplateText as OldApp_TemplateText, Video as OldApp_Video,
    Zone as OldApp_Zone, IncrusteElement as OldApp_IncrusteElement, CSSProperty as OldApp_CssProperty, IncrusteStyle as OldApp_IncrusteStyle, Media as OldApp_Media,
    Image as OldApp_Image, ZoneContent, TemplatePrice as OldApp_TemplatePrice, ZoneContent as OldApp_ZoneContent };
use Symfony\Component\HttpFoundation\{ Request, Response, JsonResponse };

use App\Repository\OldApp\{ TemplateRepository as OldApp_TemplateRepository, IncrusteRepository as OldApp_IncrusteRepository };

use App\Repository\Main\{ CustomerRepository, UserRepository,
    TemplateRepository as Main_TemplateRepository, IncrusteRepository as Main_IncrusteRepository };

use App\Service\{CSSParser as CssParser,
    DatabaseAccessRegister, TemplateStyleHandler,
    ExternalFileManager,
    IncrusteCSSHandler,
    TemplateContentsHandler,
    IncrusteHandler,
    SessionManager};



class AccueilController extends AbstractController
{


    /**
     * @var SessionManager
     */
    private $sessionManager;

    private $externalFileManager ;

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
     * @Route("/", name="template::dfdsf")
     * @Route("/home/stage/1/{token}", name="accueil::homeStage1", methods="GET",
     *     requirements = {"token": "[a-z0-9]{64}"},
     *     defaults={"token": null})
     *
     * @param Request $request
     * @param string $stage
     * @param CustomerRepository $customerRepository
     * @param UserRepository $userRepository
     * @param ExternalFileManager $externalFileManager
     * @return Response
     * @throws \Exception
     */
    public function homeStage1(Request $request, CustomerRepository $customerRepository, UserRepository $userRepository, ExternalFileManager $externalFileManager): Response
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

            return $this->redirectToRoute('template::homeStage1', [
                'stage' => 1,
                'token' => null
            ]);
        }


        elseif( is_null($request->get("token")) AND is_null($this->sessionManager->get('user')['QUICKNET']['token']) )
            throw new AccessDeniedHttpException("Access denied ! Cause : Token not found in URL and session ! ");

        if(!in_array(1, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to access this page in this stage('%d') !", $stage));

        $creatableTemplates = ['H' => [] , 'V' => []];
        $loadableTemplates = ['H' => [] , 'V' => []];

        $loadableTemplatesFromBase=($this->getDoctrine()
            ->getManager('default')
            ->getRepository(Main_Template::class )
            ->findBy( ['level' => 1 ] ));

        foreach ($loadableTemplates as $format => $loadableTemplate){
            $loadableTemplates[ $format ] = array_filter($loadableTemplatesFromBase, function($template) use ($format){
               return $template->getOrientation() === $format ;
           });
        }

        return $this->render('template/accueil/index.html.twig', [
            'user'            => $this->sessionManager->get('user'),
            'controller_name'  => 'TemplateController',
            'loadableTemplates'   => $loadableTemplates,
            'creatableTemplates' => $creatableTemplates,
            'stage'              => 1
        ]);


    }

    /**
     * @param MAIN_User $user
     * @param Request $request
     * @throws \Exception
     */
    private function updateUserSession(Main_User $user, Request $request)
    {

//        $conf = Yaml::parse($this->externalFileManager->getFileContent($this->getParameter('project_dir') . "/../admin/config/parameters.yml"));

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
//            $sessionData['QUICKNET']['RES_rep'] = $conf['sys_path']['datas'] . '/data' . '/PLAYER INFOWAY WEB/';
        $sessionData['QUICKNET']['RES_rep'] = '/data' . '/PLAYER INFOWAY WEB/';

        else
            $sessionData['QUICKNET']['RES_rep'] =  '/data_' . $user->getDatabaseName() . '/PLAYER INFOWAY WEB/';
//            $sessionData['QUICKNET']['RES_rep'] = $conf['sys_path']['datas'] . '/data_' . $user->getDatabaseName() . '/PLAYER INFOWAY WEB/';

        $this->sessionManager->replace('user', $sessionData);

    }

    /**
     * Renvoie la page d'accueil du module template
     * @Route("/home/stage/2/{token}", name="accueil::homeStage2", methods="GET",
     *     requirements = {"token": "[a-z0-9]{64}"},
     *     defaults={"token": null})
     *
     */
    public function homeStage2(Request $request, CustomerRepository $customerRepository, UserRepository $userRepository, ExternalFileManager $externalFileManager): Response
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
                'stage' => 2,
                'token' => null
            ]);
        }


        elseif( is_null($request->get("token")) AND is_null($this->sessionManager->get('user')['QUICKNET']['token']) )
            throw new AccessDeniedHttpException("Access denied ! Cause : Token not found in URL and session ! ");

        if(!in_array(2, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to access this page in this stage('%d') !", $stage));

        $creatableTemplates = ['H' => [] , 'V' => []];
        $loadableTemplates = ['H' => [] , 'V' => []];

        $loadableTemplatesFromBase=($this->getDoctrine()
            ->getManager('default')
            ->getRepository(Main_Template::class )
            ->findBy( ['level' => 1 ] ));

        foreach ($loadableTemplates as $format => $loadableTemplate){
            $loadablesTemplate[$format] = array_filter($loadableTemplatesFromBase, function($template) use ($format){
                return $template->getOrientation() === $format ;
            });
        }

        return $this->render('template/accueil/index.html.twig', [
            'user'            => $this->sessionManager->get('user'),
            'controller_name'  => 'TemplateController',
            'loadableTemplates'   => $loadableTemplates,
            'creatableTemplates' => $creatableTemplates,
            'stage'              => 2
        ]);


    }

    /**
     * Renvoie la page d'accueil du module template
     * @Route("/home/stage/3/{token}", name="accueil::homeStage3", methods="GET",
     *     requirements = {"token": "[a-z0-9]{64}"},
     *     defaults={"token": null})
     *
     */
    public function homeStage3(Request $request, CustomerRepository $customerRepository, UserRepository $userRepository, ExternalFileManager $externalFileManager): Response
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
                'stage' => 3,
                'token' => null
            ]);
        }


        elseif( is_null($request->get("token")) AND is_null($this->sessionManager->get('user')['QUICKNET']['token']) )
            throw new AccessDeniedHttpException("Access denied ! Cause : Token not found in URL and session ! ");

        if(!in_array(2, $this->sessionManager->get('user')['permissions']['access']))
            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to access this page in this stage('%d') !", $stage));

        $creatableTemplates = ['H' => [] , 'V' => []];
        $loadableTemplates = ['H' => [] , 'V' => []];

        $loadableTemplatesFromBase=($this->getDoctrine()
            ->getManager('default')
            ->getRepository(Main_Template::class )
            ->findBy( ['level' => 1 ] ));

        foreach ($loadableTemplates as $format => $loadableTemplate){
            $loadablesTemplate[$format] = array_filter($loadableTemplatesFromBase, function($template) use ($format){
                return $template->getOrientation() === $format ;
            });
        }

        return $this->render('template/accueil/index.html.twig', [
            'user'            => $this->sessionManager->get('user'),
            'controller_name'  => 'TemplateController',
            'loadableTemplates'   => $loadableTemplates,
            'creatableTemplates' => $creatableTemplates,
            'stage'              => 3
        ]);


    }



//    /**
//     * Renvoie la page d'accueil du module template
//     * @Route("/", name="template::dfdsf")
//     * @Route("/home/stage/{stage}/{token}", name="template::home", methods="GET",
//     *     requirements = {"stage" : "1|2|3", "token": "[a-z0-9]{64}"},
//     *     defaults={"token": null})
//     *
//     *
//     * @param Request $request
//     * @param string $stage
//     * @param CustomerRepository $customerRepository
//     * @param UserRepository $userRepository
//     * @param ExternalFileManager $externalFileManager
//     * @return Response
//     * @throws \Exception
//     */
//    public function home(Request $request, int $stage, CustomerRepository $customerRepository, UserRepository $userRepository, ExternalFileManager $externalFileManager): Response
//    {
//
//        if(!$this->userSessionIsInitialized())
//            throw new AccessDeniedHttpException("Access denied ! Cause : session is not started !");
//
//        elseif ($request->get("token") !== null)
//        {
//
//            $user = $userRepository->findOneByToken($request->get('token'));
//            if(!$user)
//                throw new AccessDeniedHttpException("Access denied ! Cause : Token is not valid ! ");
//
//            $this->updateUserSession($user, $request);
//            //$this->createSymLinkToUserMedia();
//
//            $user->setToken(null);
//
//            $this->getDoctrine()->getManager()->flush();
//            //$this->getDoctrine()->getManager()->clear();
//
//            unset($user);
//
//            //dd($this->sessionManager->get('user'));
//
//            return $this->redirectToRoute('template::home', [
//                'stage' => $stage,
//                'token' => null
//            ]);
//        }
//
//
//        elseif( is_null($request->get("token")) AND is_null($this->sessionManager->get('user')['QUICKNET']['token']) )
//            throw new AccessDeniedHttpException("Access denied ! Cause : Token not found in URL and session ! ");
//
//        if(!in_array($stage, $this->sessionManager->get('user')['permissions']['access']))
//            throw new AccessDeniedHttpException(sprintf("Access denied ! Cause : You're not allowed to access this page in this stage('%d') !", $stage));
//
//
//        switch($stage){
//
//            case 1 :
//                $templatesToLoad = [
//                    'create' => [] ,
//                    'load'   => [1]
//                ];
//                break;
//
//            case 2 :
//                $templatesToLoad = [
//                    'create' => [1] ,
//                    'load'   => [2]
//                ];
//                break;
//
//            case 3 :
//                $templatesToLoad = [
//                    'create' => [2] ,
//                    'load'   => [3]
//                ];
//                break;
//
//        }
//
//
//        $allLevelsToLoad = array_unique (array_filter(array_merge($templatesToLoad['create'] , $templatesToLoad['load']), function($levelToLoad){
//            return is_int($levelToLoad) && ($levelToLoad>=1 && $levelToLoad<=3) ;
//        }) );
//
//        $levelsToLoadByEnseigne = ['admin' => [],'enseigne' => []] ;
//
//        foreach($allLevelsToLoad as $levelToLoad){
//            if( $levelToLoad < 2 )$levelsToLoadByEnseigne['admin'][] = $levelToLoad;
//            else $levelsToLoadByEnseigne['enseigne'][] = $levelToLoad ;
//        }
//        $templates = [];
//
//        if( count( $levelsToLoadByEnseigne[ 'admin' ] ) > 0 ){
//            $templates=array_merge($templates,$this->getDoctrine()
//                ->getManager('default')
//                ->getRepository(Main_Template::class )
//                ->findBy( ['level' => $levelsToLoadByEnseigne[ 'admin' ] ] ));
//
//        }
//        if(count( $levelsToLoadByEnseigne[ 'enseigne' ] ) > 0) {
//
//            $templates = array_merge($templates,$this->getDoctrine()
//                ->getManager($this->sessionManager->get('user')['QUICKNET']['base'])
//                ->getRepository(OldApp_Template::class)
//                ->findBy(['level' => $levelsToLoadByEnseigne['enseigne']]));
//        }
//
//
//        $loadableTemplates = [];
//        $creatableTemplates = [];
//        foreach($templates as $template){
//            $currentLevel = $template->getLevel();
//            $currentOrientation = $template->getOrientation() ;
//            if(in_array($currentLevel,$templatesToLoad['create']))$creatableTemplates[$currentOrientation][]=$template;
//            if(in_array($currentLevel,$templatesToLoad['load']))$loadableTemplates[$currentOrientation][]=$template;
//        }
//
//        dump( $loadableTemplates, $creatableTemplates, $this->sessionManager->get('user'));
//
//        return $this->render('template/accueil/accueil.html.twig', [
//            'user'            => $this->sessionManager->get('user'),
//            'controller_name'  => 'TemplateController',
//            'loadableTemplates'   => $loadableTemplates,
//            'creatableTemplates' => $creatableTemplates,
//            'stage'              => $stage
//        ]);
//    }
//
//    private function userSessionIsInitialized()
//    {
//        return ( is_null($this->sessionManager->get('user')) ) ? false : true;
//    }
//
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

}
