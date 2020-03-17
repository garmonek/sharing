<?php
/**
 * @license MIT
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TagRepository")
 * @ORM\Table(name="tag",uniqueConstraints={@ORM\UniqueConstraint(name="name_idx",columns={"name"})})
 */
class Tag extends AbstractTimestampableEntity
{
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $name;

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
}
