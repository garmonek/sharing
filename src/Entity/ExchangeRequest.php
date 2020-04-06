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

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Offer|null
     */
    public function getTarget(): ?Offer
    {
        return $this->target;
    }

    /**
     * @param Offer|null $target
     *
     * @return $this
     */
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

    /**
     * @param Offer $proposals
     *
     * @return $this
     */
    public function addProposal(Offer $proposals): self
    {
        if (!$this->proposals->contains($proposals)) {
            $this->proposals[] = $proposals;
            $proposals->setProposedInExchangeRequests($this);
        }

        return $this;
    }

    /**
     * @param Offer $proposals
     *
     * @return $this
     */
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

    /**
     * @return string|null
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * @param string|null $message
     *
     * @return $this
     */
    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     *
     * @return $this
     */
    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
