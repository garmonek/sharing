<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 * @ORM\Table(name="district",uniqueConstraints={@ORM\UniqueConstraint(name="name_idx",columns={"name"})})
 */
class District
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="districts")
     * @ORM\JoinColumn(nullable=false)
     *
     * @var City
     */
    private $city;

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
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTimeInterface $createdAt
     *
     * @return $this
     */
    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface $updatedAt
     *
     * @return $this
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
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
