<?php


namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class MyCustomTwigExtension extends AbstractExtension
{

    /**
     * @var ParameterBagInterface
     */
    private  $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getFilters()
    {
        return [
            //new TwigFilter('', [$this, '']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('getParameter', [$this, 'getParameter']),
        ];
    }

    public function getParameter(string $name)
    {

        if(!$this->parameterBag->has($name))
            throw new \Exception(sprintf("Error ! Cause: '%s' parameter is not defined !", $name));

        return $this->parameterBag->get($name);
    }

}