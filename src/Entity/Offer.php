<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\WebImage", inversedBy="offers")
     */
    private $webImages;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Image", inversedBy="offers")
     */
    private $images;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag")
     * @ORM\JoinTable(
     *     name="offer_exchange_tags",
     *     joinColumns={@ORM\JoinColumn(name="tag_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="offer_id", referencedColumnName="id", unique=true)}
     * )
     */
    private $exchangeTags;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\District", inversedBy="offers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $district;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->webImages = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->exchangeTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|WebImage[]
     */
    public function getWebImages(): Collection
    {
        return $this->webImages;
    }

    public function addWebImage(WebImage $webImage): self
    {
        if (!$this->webImages->contains($webImage)) {
            $this->webImages[] = $webImage;
        }

        return $this;
    }

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

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
        }

        return $this;
    }

    /**
     * @return Collection|Tag[]
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
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

    public function getActive(): ?bool
    {
        return $this->active;
    }

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

    public function addExchangeTag(Tag $exchangeTag): self
    {
        if (!$this->exchangeTags->contains($exchangeTag)) {
            $this->exchangeTags[] = $exchangeTag;
        }

        return $this;
    }

    public function removeExchangeTag(Tag $exchangeTag): self
    {
        if ($this->exchangeTags->contains($exchangeTag)) {
            $this->exchangeTags->removeElement($exchangeTag);
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDistrict(): ?District
    {
        return $this->district;
    }

    public function setDistrict(?District $district): self
    {
        $this->district = $district;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
