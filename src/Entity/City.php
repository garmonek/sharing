<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 * @ORM\Table(name="city",uniqueConstraints={@ORM\UniqueConstraint(name="name_idx",columns={"name"})})
 *
 * @UniqueEntity(fields="name")
 */
class City extends AbstractTimestampableEntity
{
    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Assert\Type(type="string")
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="city", orphanRemoval=true, cascade={"remove", "persist"})
     *
     * @var ArrayCollection
     */
    private $districts;

    /**
     * City constructor.
     */
    public function __construct()
    {
        $this->districts = new ArrayCollection();
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
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    /**
     * @param District $district
     *
     * @return $this
     */
    public function addDistrict(District $district): self
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setCity($this);
        }

        return $this;
    }

    /**
     * @param District $district
     *
     * @return $this
     */
    public function removeDistrict(District $district): self
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);
            // set the owning side to null (unless already changed)
            if ($district->getCity() === $this) {
                $district->setCity(null);
            }
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function clearDistricts(): self
    {
        $this->districts = new ArrayCollection();

        return $this;
    }
}
