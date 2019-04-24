<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminReponseRepository")
 */
class AdminReponse
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $messsage;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="adminReponses")
     */
    private $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $heur;



    public function __construct()
    {
        date_default_timezone_set('Europe/Paris');
        $this->heur = date('d/m/Y à H:i:s',time());
    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getMesssage(): ?string
    {
        return $this->messsage;
    }

    public function setMesssage(string $messsage): self
    {
        $this->messsage = $messsage;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user_id;
    }

    public function setUserId(?User $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getHeur(): ?string
    {
        return $this->heur;
    }

    public function setHeur(string $heur): self
    {
        $this->heur = $heur;

        return $this;
    }


}
