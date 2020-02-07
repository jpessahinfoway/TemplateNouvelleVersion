<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\ImageRepository")
 */
class Image
{

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;


    /**
     * @ORM\Column(name="extension", type="string", length=3, nullable=false)
     */
    private $extension;

    /**
     * @ORM\Column(name="ratio", type="string", length=50, nullable=false)
     */
    private $ratio;

    /**
     * @ORM\Column(name="height", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $height;

    /**
     * @ORM\Column(name="width", type="smallint", nullable=false, options={"unsigned"=true})
     */
    private $width;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getRatio(): ?string
    {
        return $this->ratio;
    }

    public function setRatio(string $ratio): self
    {
        $this->ratio = $ratio;

        return $this;
    }

    public function getHeight(): ?int
    {
        return $this->height;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): ?int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): self
    {
        $this->media = $media;

        return $this;
    }


}
