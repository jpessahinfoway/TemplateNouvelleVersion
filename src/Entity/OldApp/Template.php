<?php

namespace App\Entity\OldApp;

use App\Entity\OldApp\Zone;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\TemplateRepository")
 * @UniqueEntity(fields="name",message="Ce nom est déjà utilisé")
 */
class Template
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
     * @ORM\Column(type="string", length=1)
     */
    private $orientation;

    /**
     * @ORM\Column(type="datetime")
     */
    private $create_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $last_modification_date;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OldApp\Zone", mappedBy="template", orphanRemoval=true)
     * @ORM\JoinColumn(onDelete="CASCADE")
     */
    private $zones;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $width;

    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $level;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $background;

    public function __construct()
    {
        $this->tags = new ArrayCollection();
        $this->zones = new ArrayCollection();
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

    public function getOrientation(): ?string
    {
        return $this->orientation;
    }

    public function setOrientation(string $orientation): self
    {
        $this->orientation = $orientation;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->create_at;
    }

    public function setCreateAt(\DateTimeInterface $create_at): self
    {
        $this->create_at = $create_at;

        return $this;
    }

    public function getLastModificationDate(): ?\DateTimeInterface
    {
        return $this->last_modification_date;
    }

    public function setLastModificationDate(?\DateTimeInterface $last_modification_date): self
    {
        $this->last_modification_date = $last_modification_date;

        return $this;
    }

    /**
     * @return Collection|Zone[]
     */
    public function getZones(): Collection
    {
        return $this->zones;
    }

    public function addZones(Zone $zones): self
    {
        if (!$this->zones->contains($zones)) {
            $this->zones[] = $zones;
            $zones->setTemplate($this);
        }

        return $this;
    }

    public function removeZones(Zone $zones): self
    {
        if ($this->zones->contains($zones)) {
            $this->zones->removeElement($zones);
            // set the owning side to null (unless already changed)
            if ($zones->getTemplate() === $this) {
                $zones->setTemplate(null);
            }
        }

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

    public function getWidth(): ?string
    {
        return $this->width;
    }

    public function setWidth(string $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getLevel(): ?int
    {
        return $this->level;
    }

    public function setLevel(int $level): self
    {
        $this->level = $level;

        return $this;
    }

    public function getBackground(): ?int
    {
        return $this->background;
    }

    public function setBackground(?int $background): self
    {
        $this->background = $background;

        return $this;
    }

    public function addZone(Zone $zone): self
    {
        if (!$this->zones->contains($zone)) {
            $this->zones[] = $zone;
            $zone->setTemplate($this);
        }

        return $this;
    }

    public function removeZone(Zone $zone): self
    {
        if ($this->zones->contains($zone)) {
            $this->zones->removeElement($zone);
            // set the owning side to null (unless already changed)
            if ($zone->getTemplate() === $this) {
                $zone->setTemplate(null);
            }
        }

        return $this;
    }
}
