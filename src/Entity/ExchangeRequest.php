<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRequestRepository")
 */
class ExchangeRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="exchangeFor")
     * @ORM\JoinColumn(nullable=false)
     */
    private $target;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $message;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Offer")
     *
     * @Assert\Count(min = 1, max = 5)
     */
    private $proposals;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="exchangeRequests")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    public function __construct()
    {
        $this->proposals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTarget(): ?Offer
    {
        return $this->target;
    }

    public function setTarget(?Offer $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }


    /**
     * @return Collection|Offer[]
     */
    public function getProposals(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Offer $proposal): self
    {
        if (!$this->proposals->contains($proposal)) {
            $this->proposals[] = $proposal;
        }

        return $this;
    }

    public function removeProposal(Offer $proposal): self
    {
        if ($this->proposals->contains($proposal)) {
            $this->proposals->removeElement($proposal);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
