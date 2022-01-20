<?php

namespace App\Entity;

use App\Repository\FollowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FollowRepository::class)]
class Follow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Membre::class, inversedBy: 'follows')]
    private $user;

    #[ORM\ManyToOne(targetEntity: Site::class, inversedBy: 'follows')]
    private $site;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?Membre
    {
        return $this->user;
    }

    public function setUser(?Membre $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSite(): ?Site
    {
        return $this->site;
    }

    public function setSite(?Site $site): self
    {
        $this->site = $site;

        return $this;
    }
}