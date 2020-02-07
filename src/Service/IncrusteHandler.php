<?php


namespace App\Service;


use Doctrine\Persistence\ObjectRepository;

class IncrusteHandler
{

    private $incrusteRepository;

    public function __construct(ObjectRepository $incrusteRepository)
    {
        $this->incrusteRepository = $incrusteRepository;
    }

    public function getIncrusteData(): array
    {

        $ressources = [
            'medias' => []
        ];
        $allIncrustes = $this->incrusteRepository->findAll();

        //dd($allIncrustes, $this->incrusteRepository);

        $allIncrusteSortedByType = $classNames = [];

        foreach($allIncrustes as $incruste)
        {
            $allIncrusteSortedByType[ $incruste->getType() ][]=$incruste;
        }

        foreach($allIncrusteSortedByType as $type => $incrusteList)
        {
            $incrusteCSSHandler = new IncrusteCSSHandler($incrusteList);
            $classNames[$type] = $incrusteCSSHandler->getIncrustesAndElementsClasses();

        }

        $rupturesSamples = [
            'il_revient_small',
            'il_revient_medium',
            'il_revient_big',
            'indisponible_small',
            'indisponible_medium',
            'indisponible_big'
        ];

        foreach($rupturesSamples as $indexRupture=>$rupture)
        {
            $ressources['medias']['ruptures'][] = [
                'id' => $indexRupture,
                'ext' => 'png',
                'filename' => $rupture
            ];
        }

        return [
            'ressources' => $ressources,
            'classNames' => $classNames
        ];

    }


    public function setDatabaseManager(ObjectRepository $incrusteRepository): self
    {
        $this->incrusteRepository = $incrusteRepository;

        return $this;
    }

}