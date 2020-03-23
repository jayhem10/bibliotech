<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 * fields={"email"},
 * message= "L'email que vous avez indiqué est déjà utilisé"
 * )
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
    private $pseudo;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min="8", minMessage="Votre mot de passe doit faire minimum 8 caractères")
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath="password", message="Vos mots de passe ne sont pas les mêmes")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Email(message="Merci de renseigner une adresse mail valable")
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Collection", mappedBy="user")
     */
    private $collection;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

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

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function eraseCredentials(){

    }

    public function getSalt(){

    }

    public function getUsername(){
         return $this->email;

    }

    public function getRoles(){
        return ['ROLE_USER'];
    }

    public function password(){
        return $this->hash;
    }

    /**
     * @return Collection|Collection[]
     */
    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function addCollection(Collection $collection): self
    {
        if (!$this->collection->contains($collection)) {
            $this->collection[] = $collection;
            $collection->setUser($this);
        }

        return $this;
    }

    public function removeCollection(Collection $collection): self
    {
        if ($this->collection->contains($collection)) {
            $this->collection->removeElement($collection);
            // set the owning side to null (unless already changed)
            if ($collection->getUser() === $this) {
                $collection->setUser(null);
            }
        }

        return $this;
    }
}
