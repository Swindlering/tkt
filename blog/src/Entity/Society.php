<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="society")
 * @ORM\Entity(repositoryClass="App\Repository\SocietyRepository")
 */
class Society
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *  type="string",
     *  length=180,
     * )
     */
    private $name;

    /**
     * @ORM\Column(
     *  type="integer",
     *  length=9,
     *  unique=true
     * )
     */
    private $siren;

    /**
     * @ORM\Column(type="string")
     */
    private $sector;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Resultat", mappedBy="society")
     */
    private $results;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }


    public function getSiren(): ?string
    {
        return $this->siren;
    }

    /**
     * @see SocietyInterface
     */
    public function getSector(): string
    {
        return (string) $this->sector;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setSector(string $sector): self
    {
        $this->sector = $sector;

        return $this;
    }

    public function setSiren(string $siren): self
    {
        $this->siren = $siren;

        return $this;
    }

    /**
     * @return Collection|Resultat[]
     */
    public function getResults(): Collection
    {
        return $this->results;
    }

    public function addResults(Resultat $resultat)
    {
        // if ($this->results->contains($resultat)) {
        //     return;
        // }

        $this->results[] = $resultat;
    }
}
