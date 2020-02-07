<?php

namespace App\Entity\Main;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\IncrusteElementRepository")
 * @ORM\Table(name="incruste_elements")
 */
class IncrusteElement
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * Many features have one product. This is the owning side.
     * @ORM\ManyToOne(targetEntity="Incruste", inversedBy="incrusteElements", cascade={"remove"})
     * @ORM\JoinColumn(name="incruste_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $incruste;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $content;

    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="IncrusteElement", mappedBy="parent", cascade={"persist","remove"})
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="IncrusteElement", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $class;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="IncrusteStyle", mappedBy="incrusteElement", cascade={"remove"})
     */
    private $incrusteStyles;

    /**
     * @ORM\Column(type="integer", length=255, nullable=true)
     */
    private $incrustOrder;



    /**
     * @ORM\Column(type="integer", nullable=false, options={"default" : 1})
     */
    private $level;

    public function __construct()
    {
        $this->incrusteStyles = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIncruste(): ?Incruste
    {
        return $this->incruste;
    }

    public function setIncruste(?Incruste $incruste): self
    {
        $this->incruste = $incruste;

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
            $incrusteStyle->setIncrusteElement($this);
        }

        return $this;
    }

    public function removeIncrusteStyle(IncrusteStyle $incrusteStyle): self
    {
        if ($this->incrusteStyles->contains($incrusteStyle)) {
            $this->incrusteStyles->removeElement($incrusteStyle);
            // set the owning side to null (unless already changed)
            if ($incrusteStyle->getIncrusteElement() === $this) {
                $incrusteStyle->setIncrusteElement(null);
            }
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return Collection|IncrusteElement[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(IncrusteElement $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(IncrusteElement $child): self
    {
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            // set the owning side to null (unless already changed)
            if ($child->getParent() === $this) {
                $child->setParent(null);
            }
        }

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    public function getIncrustOrder(): ?int
    {
        return $this->incrustOrder;
    }

    public function setIncrustOrder(?int $incrustOrder): self
    {
        $this->incrustOrder = $incrustOrder;

        return $this;
    }


    public function getLevel(): int
    {
        return $this->level;
    }


    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getZone(): ?Zone
    {
        return $this->zone;
    }

    public function setZone(?Zone $zone): self
    {
        $this->zone = $zone;

        return $this;
    }

}
