<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ImageRepository")
 * @ORM\Table(name="image",uniqueConstraints={@ORM\UniqueConstraint(name="file_idx",columns={"file"})})
 * @ORM\EntityListeners({"App\EventListener\ImageListener"})
 */
class Image extends AbstractTimestampableEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string
     */
    private $alt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="image")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var UserInterface
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Offer", mappedBy="images")
     *
     * @var ArrayCollection
     */
    private $offers;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mimeType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var UploadedFile|null
     */
    private ?UploadedFile $uploadedFile;

    /**
     * Image constructor.
     */
    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }

    /**
     * @param string $file
     *
     * @return $this
     */
    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlt(): ?string
    {
        return $this->alt;
    }

    /**
     * @param string|null $alt
     *
     * @return $this
     */
    public function setAlt(?string $alt): self
    {
        $this->alt = $alt;

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
     * @param UserInterface|null $user
     *
     * @return $this
     */
    public function setUser(?UserInterface $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    /**
     * @param Offer $offer
     *
     * @return $this
     */
    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->addImage($this);
        }

        return $this;
    }

    /**
     * @param Offer $offer
     *
     * @return $this
     */
    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            $offer->removeImage($this);
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     *
     * @return $this
     */
    public function setMimeType(?string $mimeType): self
    {
        $this->mimeType = $mimeType;

        return $this;
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
     * @return UploadedFile|null
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }

    /**
     * @param UploadedFile|null $uploadedFile
     *
     * @return $this
     */
    public function setUploadedFile(?UploadedFile $uploadedFile): self
    {
        $this->uploadedFile = $uploadedFile;

        return $this;
    }
}
