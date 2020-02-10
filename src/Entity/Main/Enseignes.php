<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\EnseignesRepository")
 */
class Enseignes
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="enseigne", type="string", length=256, nullable=false)
     */
    private $enseigne;

    /**
     * @var string
     *
     * @ORM\Column(name="datas", type="text", length=65535, nullable=false)
     */
    private $datas;

    /**
     * @var string
     *
     * @ORM\Column(name="base", type="string", length=256, nullable=false)
     */
    private $base;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEnseigne(): ?string
    {
        return $this->enseigne;
    }

    public function setEnseigne(string $enseigne): self
    {
        $this->enseigne = $enseigne;

        return $this;
    }

    public function getDatas(): ?string
    {
        return $this->datas;
    }

    public function setDatas(string $datas): self
    {
        $this->datas = $datas;

        return $this;
    }

    public function getBase(): ?string
    {
        return $this->base;
    }

    public function setBase(string $base): self
    {
        $this->base = $base;

        return $this;
    }


}
