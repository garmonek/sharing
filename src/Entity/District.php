<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 * @ORM\Table(name="district",uniqueConstraints={@ORM\UniqueConstraint(name="name_idx",columns={"name", "city_id"})})
 */
class District extends AbstractTimestampableEntity
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
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="districts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var City
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $cityId;

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

    public function getCityId(): int
    {
        return $this->cityId;
    }

    public function setCityId(int $cityId): void
    {
        $this->cityId = $cityId;
    }

    /**
     * @return City|null
     */
    public function getCity(): ?City
    {
        return $this->city;
    }

    /**
     * @param City|null $city
     *
     * @return $this
     */
    public function setCity(?City $city): self
    {
        $this->city = $city;

        return $this;
    }
}
