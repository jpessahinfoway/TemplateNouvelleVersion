<?php

namespace App\Entity\OldApp;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\TemplateStyleRepository")
 */
class TemplateStyle
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $class;

    /**
     * One product has many features. This is the inverse side.
     * @ORM\OneToMany(targetEntity="TemplateCssValue", mappedBy="style")
     */
    private $cssValues;


    /**
     * Many User have Many Phonenumbers.
     * @ORM\ManyToMany(targetEntity="App\Entity\OldApp\TemplateCssProperty")
     * @ORM\JoinTable(name="template_style_property",
     *      joinColumns={@ORM\JoinColumn(name="properties", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="template_style_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $properties;


    /**
     * One Category has Many Categories.
     * @ORM\OneToMany(targetEntity="TemplateStyle", mappedBy="parent")
     */
    private $children;

    /**
     * Many Categories have One Category.
     * @ORM\ManyToOne(targetEntity="TemplateStyle", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $type;

    public function __construct()
    {
        $this->cssValues = new ArrayCollection();
        $this->properties = new ArrayCollection();
        $this->children = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getClass(): ?string
    {
        return $this->class;
    }

    public function setClass(?string $class): self
    {
        $this->class = $class;

        return $this;
    }

    /**
     * @return Collection|TemplateCssValue[]
     */
    public function getCssValues(): Collection
    {
        return $this->cssValues;
    }

    public function addCssValue(TemplateCssValue $cssValue): self
    {
        if (!$this->cssValues->contains($cssValue)) {
            $this->cssValues[] = $cssValue;
            $cssValue->setStyle($this);
        }

        return $this;
    }

    public function removeCssValue(TemplateCssValue $cssValue): self
    {
        if ($this->cssValues->contains($cssValue)) {
            $this->cssValues->removeElement($cssValue);
            // set the owning side to null (unless already changed)
            if ($cssValue->getStyle() === $this) {
                $cssValue->setStyle(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TemplateCssProperty[]
     */
    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function addProperty(TemplateCssProperty $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties[] = $property;
        }

        return $this;
    }

    public function removeProperty(TemplateCssProperty $property): self
    {
        if ($this->properties->contains($property)) {
            $this->properties->removeElement($property);
        }

        return $this;
    }

    /**
     * @return Collection|TemplateStyle[]
     */
    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChild(TemplateStyle $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(TemplateStyle $child): self
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


    public function getType(): string
    {
        return $this->type;
    }


    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }



}
