<?php

namespace App\Entity\Main;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\ZoneContentRepository")
 */
class ZoneContent
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * One Content has One TemplateContent
     *
     * @ORM\ManyToOne(targetEntity="TemplateContent")
     * @ORM\JoinColumn(name="template_content_id", referencedColumnName="id")
     */
    private $content;


    /**
     * One ZoneContent has Many TemplateStyle
     *
     * @ORM\OneToOne(targetEntity="TemplateStyle")
     * @ORM\JoinColumn(name="template_style", referencedColumnName="id", nullable=true)
     */
    private $templateStyle;

    /**
     * @ORM\Column(type="boolean", name="is_model", options={"default": true})
     */
    private $isModel;

    public function __construct()
    {
        $this->isModel = true;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function getTemplateContent(): TemplateContent
    {
        return $this->content;
    }


    public function setTemplateContent(TemplateContent $content): self
    {
        $this->content = $content;

        return $this;
    }


    public function setTemplateStyle(?TemplateStyle $templateStyle): self
    {
        $this->templateStyle = $templateStyle;

        return $this;
    }



    public function getTemplateStyle(): ?TemplateStyle
    {
        return $this->templateStyle;
    }

    public function getIsModel()
    {
        return $this->isModel;
    }


    public function setIsModel($isModel): self
    {
        $this->isModel = $isModel;
        return $this;
    }

    public function getContent(): ?TemplateContent
    {
        return $this->content;
    }

    public function setContent(?TemplateContent $content): self
    {
        $this->content = $content;

        return $this;
    }



}
