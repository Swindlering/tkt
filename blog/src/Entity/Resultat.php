<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="result")
 */
class Resultat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    private $ca;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    private $margin;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    private $ebitda;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    private $loss;

    /**
     * @ORM\Column(
     *  type="integer",
     *  nullable=true
     * )
     */
    private $year;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Society", inversedBy="results")
     * @ORM\JoinColumn(nullable=false)
     */
    private $society;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return integer|null
     */
    public function getCa(): ?int
    {
        return $this->ca;
    }

    /**
     * @return integer|null
     */
    public function getMargin(): ?int
    {
        return $this->margin;
    }

    /**
     * @return integer|null
     */
    public function getEbitda(): ?int
    {
        return $this->ebitda;
    }

    /**
     * @return integer|null
     */
    public function getLoss(): ?int
    {
        return $this->loss;
    }

    /**
     * @return integer|null
     */
    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setCa(int $ca): self
    {
        $this->ca = $ca;
        return $this;
    }

    public function setMargin(int $margin): self
    {
        $this->margin = $margin;
        return $this;
    }

    public function setEbitda(int $ebitda): self
    {
        $this->ebitda = $ebitda;
        return $this;
    }
    public function setLoss(int $loss): self
    {
        $this->loss = $loss;
        return $this;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;
        return $this;
    }

    public function setSociety(Society $society): self
    {
        $this->society = $society;
        return $this;
    }
}
