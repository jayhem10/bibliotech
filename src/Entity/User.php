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
 * @UniqueEntity(
 * fields={"pseudo"},
 * message= "Le pseudo que vous avez indiqué est déjà utilisé"
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

    /**
     *  @var string le token qui servira lors de l'oubli de mot de passe
     * 
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;





    public function __construct()
    {
        $this->collection = new ArrayCollection();
        $this->follows = new ArrayCollection();
        $this->following = new ArrayCollection();
        $this->follower = new ArrayCollection();
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


    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }



}
