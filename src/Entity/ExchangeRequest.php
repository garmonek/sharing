<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExchangeRequestRepository")
 */
class ExchangeRequest extends AbstractTimestampableEntity
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offer", inversedBy="exchangeRequests")
     */
    private $target;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="proposedInExchangeRequests")
     */
    private $proposals;

    /**
     * @ORM\Column(type="string", length=2000, nullable=true)
     */
    private $message;

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

    /**
     * @return Collection|Offer[]
     */
    public function getProposal(): Collection
    {
        return $this->proposals;
    }

    public function addProposal(Offer $proposals): self
    {
        if (!$this->proposals->contains($proposals)) {
            $this->proposals[] = $proposals;
            $proposals->setProposedInExchangeRequests($this);
        }

        return $this;
    }

    public function removeProposal(Offer $proposals): self
    {
        if ($this->proposals->contains($proposals)) {
            $this->proposals->removeElement($proposals);
            // set the owning side to null (unless already changed)
            if ($proposals->getProposedInExchangeRequests() === $this) {
                $proposals->setProposedInExchangeRequests(null);
            }
        }

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
