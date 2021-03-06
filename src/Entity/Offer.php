<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer extends AbstractTimestampableEntity
{
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\WebImage", inversedBy="offers")
     *
     * @var ArrayCollection
     */
    private $webImages;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $userId;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", inversedBy="offers", cascade={"persist"}, fetch="EAGER")
     *
     * @Assert\Count(min = 0, max = 5)
     *
     * @var ArrayCollection
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"}, fetch="EAGER")
     *
     * @Assert\Count(min = 1, max = 5)
     *
     * @var ArrayCollection
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var ?UserInterface
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     *
     * @var bool
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", cascade={"persist"}, fetch="EAGER")
     * @ORM\JoinTable(
     *     name="offer_exchange_tags",
     *     joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="offer_id", referencedColumnName="id", unique=true)}
     * )
     *
     * @Assert\Count(min = 1, max = 5)
     *
     * @var ArrayCollection
     */
    private $exchangeTags;

    /**
     * @ORM\Column(type="text")
     *
     * @var string
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\District")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var District
     */
    private $district;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ExchangeRequest", mappedBy="target", orphanRemoval=true)
     */
    private $exchangeFor;

    /**
     *
     * Offer constructor.
     */
    public function __construct()
    {
        $this->webImages = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->exchangeTags = new ArrayCollection();
        $this->exchangeFor = new ArrayCollection();
    }

    /**
     * @return Collection|WebImage[]
     */
    public function getWebImages(): Collection
    {
        return $this->webImages;
    }

    /**
     * @param WebImage $webImage
     *
     * @return $this
     */
    public function addWebImage(WebImage $webImage): self
    {
        if (!$this->webImages->contains($webImage)) {
            $this->webImages[] = $webImage;
        }

        return $this;
    }

    /**
     * @param WebImage $webImage
     *
     * @return $this
     */
    public function removeWebImage(WebImage $webImage): self
    {
        if ($this->webImages->contains($webImage)) {
            $this->webImages->removeElement($webImage);
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clearImages(): self
    {
        $this->images = new ArrayCollection();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearTags(): self
    {
        $this->tags = new ArrayCollection();

        return $this;
    }

    /**
     * @return $this
     */
    public function clearExchangeTags(): self
    {
        $this->exchangeTags = new ArrayCollection();

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    /**
     * @param Tag $tag
     *
     * @return $this
     */
    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
        }

        return $this;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    /**
     * @param UserInterface $user
     *
     * @return $this
     */
    public function setUser(UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     *
     * @return bool|null
     */
    public function getActive(): ?bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     *
     * @return $this
     */
    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getExchangeTags(): Collection
    {
        return $this->exchangeTags;
    }

    /**
     * @param Tag $exchangeTag
     *
     * @return $this
     */
    public function addExchangeTag(Tag $exchangeTag): self
    {
        if (!$this->exchangeTags->contains($exchangeTag)) {
            $this->exchangeTags[] = $exchangeTag;
        }

        return $this;
    }

    /**
     * @param Tag $exchangeTag
     *
     * @return $this
     */
    public function removeExchangeTag(Tag $exchangeTag): self
    {
        if ($this->exchangeTags->contains($exchangeTag)) {
            $this->exchangeTags->removeElement($exchangeTag);
        }

        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     *
     * @return District|null
     */
    public function getDistrict(): ?District
    {
        return $this->district;
    }

    /**
     * @param District|null $district
     *
     * @return $this
     */
    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     */
    public function setUserId(int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ExchangeRequest[]
     */
    public function getExchangeFor(): Collection
    {
        return $this->exchangeFor;
    }

    /**
     * @param ExchangeRequest $exchangeFor
     *
     * @return $this
     */
    public function addExchangeFor(ExchangeRequest $exchangeFor): self
    {
        if (!$this->exchangeFor->contains($exchangeFor)) {
            $this->exchangeFor[] = $exchangeFor;
            $exchangeFor->setTarget($this);
        }

        return $this;
    }

    /**
     * @param ExchangeRequest $exchangeFor
     *
     * @return $this
     */
    public function removeExchangeFor(ExchangeRequest $exchangeFor): self
    {
        if ($this->exchangeFor->contains($exchangeFor)) {
            $this->exchangeFor->removeElement($exchangeFor);
            // set the owning side to null (unless already changed)
            if ($exchangeFor->getTarget() === $this) {
                $exchangeFor->setTarget(null);
            }
        }

        return $this;
    }
}
