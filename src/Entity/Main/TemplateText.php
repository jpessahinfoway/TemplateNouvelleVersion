<?php


namespace App\Entity\Main;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\TemplateTextRepository")
 */
class TemplateText extends TemplateContent
{
    
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $content;



    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }
}