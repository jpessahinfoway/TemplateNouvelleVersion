<?php
/**
 * Created by PhpStorm.
 * User: Serge
 * Date: 04/11/2019
 * Time: 14:37
 */
namespace App\Service;


use Sabberworm\CSS\OutputFormatTest;
use Sabberworm\CSS\Parser;

class CSSParser
{
    private $_path;
    private $_CSSContent;
    private $_parser;
    private $_parsedCSS;


    public function setPath($path){
        $this->_path = $path;
        $this->_parsedCSS = [];
    }

    public function getContent(){
        $this->_CSSContent = file_get_contents($this->_path);
    }

    public function addClass($className,$content){

        $content = str_replace(' ', '',$content);
        $explodedContent = explode(';',$content);

        file_put_contents($this->_path,PHP_EOL.'.'.$className .' {'.PHP_EOL,FILE_APPEND);

        foreach($explodedContent as $property){
            if(strlen($property) > 1){
                file_put_contents($this->_path,'    ' . str_replace(':', ' : ' , $property) . ' ;' . PHP_EOL,FILE_APPEND);
            }
        }

        file_put_contents($this->_path,'}',FILE_APPEND);
    }

    public function format(){

        $this->_parser =  new Parser($this->_CSSContent);
        //var_dump(OutputFormatTest::testPlain());

        $rulesSets =$this->_parser->parse()->getAllRuleSets();
        foreach($rulesSets as $declarationBlock){
            foreach($declarationBlock->getRules() as $rule){
                $currentSelector = $declarationBlock->getSelectors()[0]->getSelector();
                if(!isset($this->_parsedCSS[$currentSelector]))$this->_parsedCSS[$currentSelector]=[];
                $this->_parsedCSS[$declarationBlock->getSelectors()[0]->getSelector()][$rule->getRule()]=($rule->getValue());
            }
        }
    }
    public function parseCSS($path){
        $this->setPath($path);
        $this->getContent();
        $this->format();

        return $this->_parsedCSS;

    }

}