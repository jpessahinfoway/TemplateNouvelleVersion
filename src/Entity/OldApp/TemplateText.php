<?php


namespace App\Entity\OldApp;
use Doctrine\ORM\Mapping as ORM;
use \App\Entity\OldApp\TemplateContent;


/**
 * @ORM\Entity(repositoryClass="App\Repository\OldApp\TemplateTextRepository")
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