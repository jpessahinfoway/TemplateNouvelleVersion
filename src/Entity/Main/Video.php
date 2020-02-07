<?php

namespace App\Entity\Main;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Main\VideoRepository")
 */
class Video
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * One Product has One Shipment.
     * @ORM\OneToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;



    /**
     * @ORM\Column(type="string", length=3, nullable=false,)
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=30, nullable=false,)
     */
    private $format;

    /**
     * @ORM\Column(type="string", length=30, nullable=false,)
     */
    private $ratio;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $height;

    /**
     * @ORM\Column(type="integer", nullable=false, options={"unsigned"=true})
     */
    private $width;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, name="sampleSize")
     */
    private $samplesize;

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $encoder;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, name="videoCodec")
     */
    private $videocodec;

    /**
     * @ORM\Column(type="string", length=10, nullable=false, name="videoCodecLevel")
     */
    private $videocodeclevel;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, name="videoFrequence")
     */
    private $videofrequence;

    /**
     * @ORM\Column(type="smallint", nullable=false, name="videoFrames")
     */
    private $videoframes;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, name="videoDebit")
     */
    private $videodebit;

    /**
     * @ORM\Column(type="string", length=30, nullable=true, name="audioCodec")
     */
    private $audiocodec;

    /**
     * @ORM\Column(type="string", length=20, nullable=false, name="audioDebit")
     */
    private $audiodebit;

    /**
     * @ORM\Column(type="string", length=20, nullable=true, name="audioFrequence")
     */
    private $audiofrequence;

    /**
     * @ORM\Column(type="boolean", nullable=true, name="audioChannel")
     */
    private $audiochannel;

    /**
     * @ORM\Column(type="smallint", nullable=true, name="audioFrames", options={"unsigned"=true})
     */
    private $audioframes;

    /**
     * @ORM\Column(type="string", length=30, nullable=false)
     */
    private $duration;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(Media $media): self
    {
        $this->media = $media;

        return $this;
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

    public function getFormat(): ?string
    {
        return $this->format;
    }

    public function setFormat(string $format): self
    {
        $this->format = $format;

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

    public function getSamplessize(): ?string
    {
        return $this->samplesize;
    }

    public function setSampleSize(string $sampleSize): self
    {
        $this->samplesize = $sampleSize;

        return $this;
    }

    public function getEncoder(): ?string
    {
        return $this->encoder;
    }

    public function setEncoder(string $encoder): self
    {
        $this->encoder = $encoder;

        return $this;
    }

    public function getVideocodec(): ?string
    {
        return $this->videocodec;
    }

    public function setVideocodec(string $videocodec): self
    {
        $this->videocodec = $videocodec;

        return $this;
    }

    public function getVideocodeclevel(): ?string
    {
        return $this->videocodeclevel;
    }

    public function setVideocodeclevel(string $videocodeclevel): self
    {
        $this->videocodeclevel = $videocodeclevel;

        return $this;
    }

    public function getVideofrequence(): ?string
    {
        return $this->videofrequence;
    }

    public function setVideofrequence(string $videofrequence): self
    {
        $this->videofrequence = $videofrequence;

        return $this;
    }

    public function getVideoframes(): ?int
    {
        return $this->videoframes;
    }

    public function setVideoframes(int $videoframes): self
    {
        $this->videoframes = $videoframes;

        return $this;
    }

    public function getVideodebit(): ?string
    {
        return $this->videodebit;
    }

    public function setVideodebit(string $videodebit): self
    {
        $this->videodebit = $videodebit;

        return $this;
    }

    public function getAudiocodec(): ?string
    {
        return $this->audiocodec;
    }

    public function setAudiocodec(?string $audiocodec): self
    {
        $this->audiocodec = $audiocodec;

        return $this;
    }

    public function getAudiodebit(): ?string
    {
        return $this->audiodebit;
    }

    public function setAudiodebit(string $audiodebit): self
    {
        $this->audiodebit = $audiodebit;

        return $this;
    }

    public function getAudiofrequence(): ?string
    {
        return $this->audiofrequence;
    }

    public function setAudiofrequence(?string $audiofrequence): self
    {
        $this->audiofrequence = $audiofrequence;

        return $this;
    }

    public function getAudiochannel(): ?bool
    {
        return $this->audiochannel;
    }

    public function setAudiochannel(?bool $audiochannel): self
    {
        $this->audiochannel = $audiochannel;

        return $this;
    }

    public function getAudioframes(): ?int
    {
        return $this->audioframes;
    }

    public function setAudioframes(?int $audioframes): self
    {
        $this->audioframes = $audioframes;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getSamplesize(): ?string
    {
        return $this->samplesize;
    }
}
