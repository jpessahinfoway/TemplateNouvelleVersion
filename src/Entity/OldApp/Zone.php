<?php

namespace App\Entity\OldApp;

use App\Entity\OldApp\Template;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\ZoneRepository")
 * @UniqueEntity(fields="name",message="Ce nom est déjà utilisé")
 */
class Zone
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;


    /**
     * @ORM\Column(type="boolean")
     */
    private $isBlocked;


    /**
     * Many Zones have One Background.
     * @ORM\ManyToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="background_id", nullable=true, referencedColumnName="id")
     */
    private $background;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OldApp\Template", inversedBy="zone")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position_top;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position_left;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $z_index;

    /**
     * One Zone has Many Zones(children).
     * @ORM\OneToMany(targetEntity="App\Entity\OldApp\Zone", mappedBy="parent")
     */
    private $children;

    /**
     * Many Zones(children) have One Zone(parent).
     * @ORM\ManyToOne(targetEntity="App\Entity\OldApp\Zone", inversedBy="children", cascade={"persist"})
     * @ORM\JoinColumn(name="parent_id", nullable=true, referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $position;

    /**
     * One Zone has One Content
     *
     * @ORM\OneToOne(targetEntity="ZoneContent")
     * @ORM\JoinColumn(name="content_id", referencedColumnName="id")
     */
    private $content;




    public function __construct()
    {
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;

        return $this;
    }
    

    public function getBackground(): ?Media
    {
        return $this->background;
    }

    public function setBackground(?Media $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    public function setTemplate(?Template $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getPositionTop(): ?string
    {
        return $this->position_top;
    }

    public function setPositionTop(string $position_top): self
    {
        $this->position_top = $position_top;

        return $this;
    }

    public function getPositionLeft(): ?string
    {
        return $this->position_left;
    }

    public function setPositionLeft(string $position_left): self
    {
        $this->position_left = $position_left;

        return $this;
    }

    public function getZIndex(): ?string
    {
        return $this->z_index;
    }

    public function setZIndex(string $z_index): self
    {
        $this->z_index = $z_index;

        return $this;
    }

    public function getChildren(): Collection
    {
        return $this->children;
    }

    public function addChildren(Zone $zone): self
    {
        if (!$this->children->contains($zone)) {
            $this->children[] = $zone;
            $zone->setParent($this);
        }

        return $this;
    }

    public function removeChildren(Zone $zone): self
    {
        if ($this->children->contains($zone)) {
            $this->children->removeElement($zone);
            // set the owning side to null (unless already changed)
            if ($zone->getParent() === $this) {
                $zone->setParent(null);
            }
        }

        return $this;
    }

    public function getParent(): ?Zone
    {
        return $this->parent;
    }


    public function setParent(?Zone $parent) : self
    {
        $this->parent = $parent;
        return $this;
    }

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function addChild(Zone $child): self
    {
        if (!$this->children->contains($child)) {
            $this->children[] = $child;
            $child->setParent($this);
        }

        return $this;
    }

    public function removeChild(Zone $child): self
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

    public function getContent(): ?ZoneContent
    {
        return $this->content;
    }


    public function setContent(ZoneContent $zoneContent): self
    {
        $this->content = $zoneContent;
        $zoneContent->setZone($this);

        return $this;
    }

}
