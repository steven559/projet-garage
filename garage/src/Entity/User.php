<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
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
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numero;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\RendezVous", mappedBy="User")
     */
    private $rendezVouses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\AdminReponse", mappedBy="user_id")
     */
    private $adminReponses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $incriptionToken;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
        $this->adminReponses = new ArrayCollection();
    }
    public function __toString()
    {
        // TODO: Implement __toString() method.
        return $this->getEmail();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): self
    {
        $this->numero = $numero;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->Role;
    }

    public function setRole(string $Role): self
    {
        $this->Role = $Role;

        return $this;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return ['ROLE_USER'];
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
        if ($this->Role == 'ROLE_USER') {
            return ['ROLE_USER'];

        }
        elseif ($this->Role == 'ROLE_ADMIN') {
            return ['ROLE_ADMIN'];
        } else {
            return [];
        }
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.

    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @return Collection|RendezVous[]
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): self
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses[] = $rendezVouse;
            $rendezVouse->setUser($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->removeElement($rendezVouse);
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getUser() === $this) {
                $rendezVouse->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdminReponse[]
     */
    public function getAdminReponses(): Collection
    {
        return $this->adminReponses;
    }

    public function addAdminReponse(AdminReponse $adminReponse): self
    {
        if (!$this->adminReponses->contains($adminReponse)) {
            $this->adminReponses[] = $adminReponse;
            $adminReponse->setUserId($this);
        }

        return $this;
    }

    public function removeAdminReponse(AdminReponse $adminReponse): self
    {
        if ($this->adminReponses->contains($adminReponse)) {
            $this->adminReponses->removeElement($adminReponse);
            // set the owning side to null (unless already changed)
            if ($adminReponse->getUserId() === $this) {
                $adminReponse->setUserId(null);
            }
        }

        return $this;
    }

    public function getIncriptionToken(): ?string
    {
        return $this->incriptionToken;
    }

    public function setIncriptionToken(?string $incriptionToken): self
    {
        $this->incriptionToken = $incriptionToken;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

        return $this;
    }
}
