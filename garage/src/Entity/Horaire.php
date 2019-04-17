<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HoraireRepository")
 */
class Horaire
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $jour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $matin;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $midi;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMatin(): ?string
    {
        return $this->matin;
    }

    public function setMatin(string $matin): self
    {
        $this->matin = $matin;

        return $this;
    }

    public function getMidi(): ?string
    {
        return $this->midi;
    }

    public function setMidi(string $midi): self
    {
        $this->midi = $midi;

        return $this;
    }
}
