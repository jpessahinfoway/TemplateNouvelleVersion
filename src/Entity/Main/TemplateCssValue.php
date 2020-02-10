<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\TemplateCssValueRepository")
 */
class TemplateCssValue
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="TemplateStyle", inversedBy="cssValues")
     * @ORM\JoinColumn(name="style_id", referencedColumnName="id")
     */
    private $style;

    /**
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="TemplateCssProperty")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    private $property;

    /**
     * One Product has One Shipment.
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getStyle(): ?TemplateStyle
    {
        return $this->style;
    }

    public function setStyle(?TemplateStyle $style): self
    {
        $this->style = $style;

        return $this;
    }

    public function getProperty(): ?TemplateCssProperty
    {
        return $this->property;
    }

    public function setProperty(?TemplateCssProperty $property): self
    {
        $this->property = $property;

        return $this;
    }
}
