<?php

namespace App\Service;

class TemplateStyleHandler {


    public function __construct($styles)
    {
        $this->temlateStyles= $styles;
    }


    public function generateCss()
    {
        $css = [];

        foreach ($this->temlateStyles as $temlateStyle)
        {

            $css = "." . $temlateStyle->getClass() . " { ";

            foreach ($temlateStyle->getCssValues()->getValues() as $templateCssValue)
            {

                if($templateCssValue->getProperty()->getName() === 'rotate')
                {
                    $css .= ' transform : rotate(' . $templateCssValue->getValue() . "deg); ";
                }
                else
                {
                    $css .= $templateCssValue->getProperty()->getName() . ' : ' . $templateCssValue->getValue() . "; ";
                }

            }

            $css .= " }";
            
        }

        return $css;

    }


}