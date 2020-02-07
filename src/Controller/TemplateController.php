<?php

namespace App\Controller;
use App\Entity\TemplateRessources\Incruste;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TemplateController extends AbstractController
{


    /**
     * @Route("/template/stage2/test", name="templateStage2",methods="GET")
     */
    public function stage2Creation(\Symfony\Component\HttpFoundation\Request $request)
    {


        $manager =  $this->getDoctrine()->getManager('addons') ;
        $repo = $manager->getRepository(Incruste::class) ;
        $incrustes = $repo->findAll();
        dump($incrustes);
            die;


        return $this->render('template/stages/index.html.twig', [

        ]);
    }


}
