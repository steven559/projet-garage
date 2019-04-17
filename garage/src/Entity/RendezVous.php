<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RendezVousRepository")
 */
class RendezVous
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="rendezVouses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heurDepart;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heurFin;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): self
    {
        $this->jour = $jour;

        return $this;
    }

    public function getHeurDepart(): ?string
    {
        return $this->heurDepart;
    }

    public function setHeurDepart(string $heurDepart): self
    {
        $this->heurDepart = $heurDepart;

        return $this;
    }

    public function getHeurFin(): ?string
    {
        return $this->heurFin;
    }

    public function setHeurFin(string $heurFin): self
    {
        $this->heurFin = $heurFin;

        return $this;
    }
}
