<?php


namespace App\DataFixtures;
require __DIR__.'/../../vendor/autoload.php';


use App\Entity\TemplateRessources\CSSProperty;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;


class CssPropertiesFixtures extends Fixture
{

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {

        $names = [
            'background',
            'font-size',
            'font-weight',
            'color',
            'text-decoration',
            'transform',
            'font-family',
            'text-align',
            'initial-text'
        ];

        foreach ($names as $name)
        {

            $cssProperties = new CSSProperty();
            $cssProperties->setName($name);

            $manager->persist($cssProperties);
        }

        $manager->flush();
    }
}