<?php
/**
 * @license MIT
 */

namespace App\Entity;

use DateTimeInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user",uniqueConstraints={@ORM\UniqueConstraint(name="email_idx",columns={"email"})})
 *
 * @UniqueEntity("email", groups={"registration"}, message="email_in_use")
 * @UniqueEntity("username", groups={"registration"}, message="username_in_use")
 */
class User implements UserInterface
{
    /**
     * @var int
     */
    public const NUMBER_OF_ITEMS = 10;

    /**
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=80)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Regex("/^[\p{L}_\d ]+$/", groups={"registration"})
     * @Assert\Length(min=1, max=80, groups={"registration"})
     *
     * @var string
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Length(
     *     min="3",
     *     max="255",
     *    groups={"registration"}
     * )
     *
     * @SecurityAssert\UserPassword
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string",length=255)
     *
     * @Assert\NotBlank(groups={"registration"})
     * @Assert\Email(groups={"registration"})
     *
     * @var string
     */
    private $email;

    /**
     * @Gedmo\Timestampable(on="create")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @var DateTimeInterface
     */
    private $createdAt;

    /**
     * @Gedmo\Timestampable(on="update")
     *
     * @ORM\Column(type="datetime")
     *
     * @Assert\DateTime
     *
     * @var DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WebImage", mappedBy="user", orphanRemoval=true)
     */
    private $webImages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="user", orphanRemoval=true)
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="user", orphanRemoval=true)
     */
    private $offers;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
        $this->webImages = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    /**
     * @see UserInterface
     *
     * @inheritdoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @see UserInterface
     *
     * @inheritdoc
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return $this
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return $this
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return $this
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     */
    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeInterface|null $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return DateTimeInterface|null
     *
     */
    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeInterface|null $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Role[]
     */
    public function getRoles(): Collection
    {
        return $this->roles;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function addRole(Role $role): self
    {
        if (!$this->roles->contains($role)) {
            $this->roles[] = $role;
            $role->setUser($this);
        }

        return $this;
    }

    /**
     * @param Role $role
     *
     * @return $this
     */
    public function removeRole(Role $role): self
    {
        if ($this->roles->contains($role)) {
            $this->roles->removeElement($role);
            // set the owning side to null (unless already changed)
            if ($role->getUser() === $this) {
                $role->setUser(null);
            }
        }

        return $this;
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
            $webImage->setUser($this);
        }

        return $this;
    }

    public function removeWebImage(WebImage $webImage): self
    {
        if ($this->webImages->contains($webImage)) {
            $this->webImages->removeElement($webImage);
            // set the owning side to null (unless already changed)
            if ($webImage->getUser() === $this) {
                $webImage->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getImage(): Collection
    {
        return $this->image;
    }

    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setUser($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->image->contains($image)) {
            $this->image->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getUser() === $this) {
                $image->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setUser($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            // set the owning side to null (unless already changed)
            if ($offer->getUser() === $this) {
                $offer->setUser(null);
            }
        }

        return $this;
    }
}
