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
     * @ORM\OneToOne(targetEntity="Media")
     * @ORM\JoinColumn(name="media", referencedColumnName="id")
     */
    private $media;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=3, nullable=false)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="format", type="string", length=30, nullable=false)
     */
    private $format;

    /**
     * @var string
     *
     * @ORM\Column(name="ratio", type="string", length=30, nullable=false)
     */
    private $ratio;

    /**
     * @var int
     *
     * @ORM\Column(name="height", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $height;

    /**
     * @var int
     *
     * @ORM\Column(name="width", type="integer", nullable=false, options={"unsigned"=true})
     */
    private $width;

    /**
     * @var string
     *
     * @ORM\Column(name="sampleSize", type="string", length=20, nullable=false)
     */
    private $samplesize;

    /**
     * @var string
     *
     * @ORM\Column(name="encoder", type="string", length=30, nullable=false)
     */
    private $encoder;

    /**
     * @var string
     *
     * @ORM\Column(name="videoCodec", type="string", length=20, nullable=false)
     */
    private $videocodec;

    /**
     * @var string
     *
     * @ORM\Column(name="videoCodecLevel", type="string", length=10, nullable=false)
     */
    private $videocodeclevel;

    /**
     * @var string
     *
     * @ORM\Column(name="videoFrequence", type="string", length=20, nullable=false)
     */
    private $videofrequence;

    /**
     * @var int
     *
     * @ORM\Column(name="videoFrames", type="smallint", nullable=false)
     */
    private $videoframes;

    /**
     * @var string
     *
     * @ORM\Column(name="videoDebit", type="string", length=20, nullable=false)
     */
    private $videodebit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="audioCodec", type="string", length=30, nullable=true)
     */
    private $audiocodec;

    /**
     * @var string|null
     *
     * @ORM\Column(name="audioDebit", type="string", length=20, nullable=true)
     */
    private $audiodebit;

    /**
     * @var string|null
     *
     * @ORM\Column(name="audioFrequence", type="string", length=20, nullable=true)
     */
    private $audiofrequence;

    /**
     * @var bool|null
     *
     * @ORM\Column(name="audioChannel", type="boolean", nullable=true)
     */
    private $audiochannel;

    /**
     * @var int|null
     *
     * @ORM\Column(name="audioFrames", type="smallint", nullable=true, options={"unsigned"=true})
     */
    private $audioframes;

    /**
     * @var string
     *
     * @ORM\Column(name="duration", type="string", length=30, nullable=false)
     */
    private $duration;

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

    public function getSamplesize(): ?string
    {
        return $this->samplesize;
    }

    public function setSamplesize(string $samplesize): self
    {
        $this->samplesize = $samplesize;

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

    public function setAudiodebit(?string $audiodebit): self
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
