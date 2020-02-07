<?php
/**
 * Created by PhpStorm.
 * User: Serge
 * Date: 22/11/2019
 * Time: 12:36
 */


namespace App\Service;


use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class IncrusteCSSHandler
{

    private $_generatedCSS;

    private $_incrustes;

    private $_serializer;

    private $_classNames = [];
    private $_incrustesAndElementsClasses = [];

    public function __construct($incrustes)
    {
        $this->_incrustes = $incrustes;
        $this->_parsedIncrustes = [];
        $this->_incrustesAndElementsClasses = [];


       // $this->buildParsedincrustes();
        $this->generateCSS();

    }

    public function generateSerializer(){
        $encoder = new JsonEncoder();
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object, $format, $context) {
                return $object->getId();
            },
        ];
        $normalizer = new ObjectNormalizer(null, null, null, null, null, null, $defaultContext);

        $this->_serializer = new Serializer([$normalizer], [$encoder]);

    }

    public function buildParsedincrustes(){
        $this->generateSerializer();
       /* foreach($this->_incrustes as $incruste){
            dump($incruste);
        }
        dump($this->_incrustes);
        $this->_parsedIncrustes = json_decode($this->_serializer->serialize($this->_incrustes, 'json'));*/

    }

    /**
     * @return array
     */
    public function getClassNames(): array
    {
        return $this->_classNames;
    }

    /**
     * @param array $classNames
     */
    public function setClassNames(array $classNames): void
    {
        $this->_classNames = $classNames;
    }

    public function generateCSS(){

        foreach($this->_incrustes as $indexIncruste=>$incruste){



            $this->_incrustesAndElementsClasses[$indexIncruste]=[
                'name' => $incruste->getName(),
                'type' => $incruste->getType(),
                'id'  => $incruste->getId(),
                'elements' => []
                ];

            $incrusteElements = $incruste->getIncrusteElements();

            foreach($incrusteElements as $indexElement => $incrusteElement){



                $this->_incrustesAndElementsClasses[$indexIncruste]['elements'][$incrusteElement->getId()]=[
                    'type' => $incrusteElement->getType(),
                    'id'  => $incrusteElement->getId(),
                    'content' => $incrusteElement->getContent(),
                    'class' => $incrusteElement->getClass(),
                    'parent' => $incrusteElement->getParent()!==null?$incrusteElement->getParent()->getId() : null,
                    'incrustOrder' => $incrusteElement->getIncrustOrder(),
                    'childrens' => []
                ];



                $this->_classNames[] = [
                    'name' => $this->_incrustesAndElementsClasses[$indexIncruste]['elements'][$incrusteElement->getId()]['class'],
                    'id'   => $this->_incrustesAndElementsClasses[$indexIncruste]['elements'][$incrusteElement->getId()]['id']
                ];


                $this->_generatedCSS .= ".".$incrusteElement->getClass()." { ";

                foreach ($incrusteStyles = $incrusteElement->getIncrusteStyles() as $incrusteStyle){
                    $this->_generatedCSS .= $incrusteStyle->getProperty()->getName() .' : ' . $incrusteStyle->getValue() .' ; ';
                }

                $this->_generatedCSS .= ' } ';
            }

                //dump($this->_incrustesAndElementsClasses[$indexIncruste]['elements']);


            foreach($this->_incrustesAndElementsClasses[$indexIncruste]['elements'] as $indexElementIncruste=>$elementIncruste){
                if($elementIncruste['parent'] !== null)$this->_incrustesAndElementsClasses[$indexIncruste]['elements'][$elementIncruste['parent']]['childrens'][]=$elementIncruste;
            }

            $incrustElementsArrayWithoutChildrens = array_values(array_filter(
                $this->_incrustesAndElementsClasses[$indexIncruste]['elements'],
                function($elementIncruste){
                    return $elementIncruste['parent']===null;
                }));
            $incrustElementsArrayWithoutChildrensSorted = array_map(function($incrustElementArrayWithoutChildrens){
                usort($incrustElementArrayWithoutChildrens['childrens'], function($childA,$childB){return $childA['incrustOrder'] > $childB['incrustOrder'];});
                return $incrustElementArrayWithoutChildrens;
            },$incrustElementsArrayWithoutChildrens);

            $this->_incrustesAndElementsClasses[$indexIncruste]['elements']=$incrustElementsArrayWithoutChildrensSorted;




          /*  array_map(function($current){
               return $current;
            },$this->_incrustesAndElementsClasses['elements']);*/


        }


    }

    /**
     * @return array
     */
    public function getIncrustesAndElementsClasses(): array
    {
        return $this->_incrustesAndElementsClasses;
    }

    /**
     * @param array $incrustesAndElementsClasses
     */
    public function setIncrustesAndElementsClasses(array $incrustesAndElementsClasses): void
    {
        $this->_incrustesAndElementsClasses = $incrustesAndElementsClasses;
    }

    /**
     * @return array
     */
    public function getParsedIncrustes(): array
    {
        return $this->_parsedIncrustes;
    }

    /**
     * @param array $parsedIncrustes
     */
    public function setParsedIncrustes(array $parsedIncrustes): void
    {
        $this->_parsedIncrustes = $parsedIncrustes;
    }

    /**
     * @return mixed
     */
    public function getGeneratedCSS()
    {
        return $this->_generatedCSS;
    }

    /**
     * @param mixed $generatedCSS
     */
    public function setGeneratedCSS($generatedCSS): void
    {
        $this->_generatedCSS = $generatedCSS;
    }

    /**
     * @return mixed
     */
    public function getIncrustes()
    {
        return $this->_incrustes;
    }

    /**
     * @param mixed $incrustes
     */
    public function setIncrustes($incrustes): void
    {
        $this->_incrustes = $incrustes;
    }



}