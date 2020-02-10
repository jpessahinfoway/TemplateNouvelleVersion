<?php

namespace App\Entity\OldApp;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\CSSPropertyRepository")
 * @ORM\Table(name="css_properties")
 */
class CSSProperty
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="IncrusteStyle", mappedBy="property")
     */
    private $incrusteStyles;

    public function __construct()
    {
        $this->incrusteStyles = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|IncrusteStyle[]
     */
    public function getIncrusteStyles(): Collection
    {
        return $this->incrusteStyles;
    }

    public function addIncrusteStyle(IncrusteStyle $incrusteStyle): self
    {
        if (!$this->incrusteStyles->contains($incrusteStyle)) {
            $this->incrusteStyles[] = $incrusteStyle;
            $incrusteStyle->setProperty($this);
        }

        return $this;
    }

    public function removeIncrusteStyle(IncrusteStyle $incrusteStyle): self
    {
        if ($this->incrusteStyles->contains($incrusteStyle)) {
            $this->incrusteStyles->removeElement($incrusteStyle);
            // set the owning side to null (unless already changed)
            if ($incrusteStyle->getProperty() === $this) {
                $incrusteStyle->setProperty(null);
            }
        }

        return $this;
    }
}
