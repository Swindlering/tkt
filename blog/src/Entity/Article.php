<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="article")
 */
Class Article
{

    public function __construct()
    {
        $this->created = new \DateTime("now");
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *  type="string",
     *  length=255,
     *  unique=true
     * )
     */
    private $title;

    /**
     * @ORM\Column(
     *  type="string",
     *  length=255,
     *  nullable=true
     * )
     */
    private $subtitle;

    /**
     * @ORM\Column(
     *  type="string",
     * )
     */
    private $content;

    /**
     * @ORM\Column(
     *  type="datetime",
     *  nullable=true
     * )
     */
    private $updated;

    /**
     * @ORM\Column(
     *  type="datetime",
     *  nullable=false
     * )
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function getSubtitle(): ?string
    {
        return $this->subtitle;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getUpdatedDate(): ?DateTime
    {
        return $this->updated;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->created;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function setSubtitle(string $subtitle): self
    {
        $this->subtitle = $subtitle;
        return $this;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function setUpdated(): self
    {
        $this->updated = new \DateTime("now");
        return $this;
    }

    public function setCreated(): self
    {
        $this->created = new \DateTime("now");
        return $this;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }
}
