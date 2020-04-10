<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FollowRepository")
 */
class Follow
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $follower;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $following;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFollower(): ?int
    {
        return $this->follower;
    }

    public function setFollower(int $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowing(): ?User
    {
        return $this->following;
    }

    public function setFollowing(?User $following): self
    {
        $this->following = $following;

        return $this;
    }


}