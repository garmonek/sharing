<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
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
class User extends AbstractTimestampableEntity implements UserInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\Role", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\WebImage", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $webImages;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Image", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Offer", mappedBy="user", orphanRemoval=true)
     *
     * @var ArrayCollection
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
     * @return Role[]
     */
    public function getRoles()
    {
        $roles = [];
        foreach ($this->roles as $role) {
            $roles[] = $role->getRole();
        }
        $roles[] = static::ROLE_USER;

        return array_unique($roles);
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

    /**
     * @param WebImage $webImage
     *
     * @return $this
     */
    public function addWebImage(WebImage $webImage): self
    {
        if (!$this->webImages->contains($webImage)) {
            $this->webImages[] = $webImage;
            $webImage->setUser($this);
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

    /**
     * @param Image $image
     *
     * @return $this
     */
    public function addImage(Image $image): self
    {
        if (!$this->image->contains($image)) {
            $this->image[] = $image;
            $image->setUser($this);
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

    /**
     * @param Offer $offer
     *
     * @return $this
     */
    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->setUser($this);
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
            // set the owning side to null (unless already changed)
            if ($offer->getUser() === $this) {
                $offer->setUser(null);
            }
        }

        return $this;
    }
}
