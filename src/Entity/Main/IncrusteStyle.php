<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\IncrusteStyleRepository")
 * @ORM\Table(name="incruste_styles")
 */
class IncrusteStyle
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $value;



    /**
     * One Product has One Shipment.
     * @ORM\ManyToOne(targetEntity="CSSProperty", inversedBy="incrusteStyles")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id")
     */
    private $property;

    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="IncrusteElement", inversedBy="incrusteStyles", cascade={"remove"})
     * @ORM\JoinColumn(name="incruste_element_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $incrusteElement;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }


    public function getProperty(): ?CSSProperty
    {
        return $this->property;
    }

    public function setProperty(?CSSProperty $property): self
    {
        $this->property = $property;

        return $this;
    }

    public function getIncrusteElement(): ?IncrusteElement
    {
        return $this->incrusteElement;
    }

    public function setIncrusteElement(?IncrusteElement $incrusteElement): self
    {
        $this->incrusteElement = $incrusteElement;

        return $this;
    }
}
