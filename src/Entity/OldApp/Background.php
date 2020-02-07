<?php

namespace App\Entity\OldApp;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\BackgroundRepository")
 */
class Background
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\OldApp\Media")
     */
    private $content;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\OldApp\TemplateStyle", cascade={"persist", "remove"})
     */
    private $style;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?Media
    {
        return $this->content;
    }

    public function setContent(?Media $content): self
    {
        $this->content = $content;

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
}
