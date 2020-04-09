<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\AbstractTimestampableEntity;
use App\Entity\City;
use App\Entity\District;
use App\Entity\Tag;

/**
 * Class Criteria
 */
class OfferCriteria extends AbstractCriteria
{
    public const SORT_DIRECTION_ASC = 1;
    public const SORT_DIRECTION_DESC = 2;

    public const OFFER_ACTIVE = 1;
    public const OFFER_INACTIVE = 2;
    public const OFFER_ALL = 3;

    public const SORT_VALUE_CREATED = 1;
    public const SORT_VALUE_UPDATED = 2;
    public const SORT_VALUE_EXCHANGE_TAGS = 3;
    public const SORT_VALUE_TAGS = 4;

    /**
     * @var Tag[]
     */
    public $tags = [];

    /**
     * @var District[]
     */
    public $districts = [];

    /**
     * @var ?City
     */
    public $city;

    /**
     * @var Tag[]
     */
    public $exchangeTags = [];

    /**
     * @var bool
     */
    public $active = null;

    /**
     * @var int
     */
    public $sortDirection;

    /**
     * @var int
     */
    public $sortValue;

    /**
     * @var ?int
     */
    public $userId = null;

    /**
     * @return bool
     */
    public function hasTags(): bool
    {
        return 0 !== count($this->tags);
    }

    public function getCityId(): ?int
    {
        if ($this->city instanceof City) {
            return $this->city->getId();
        }

        return null;
    }

    /**
     * @return bool
     */
    public function hasExchangeTags(): bool
    {
        return 0 !== count($this->exchangeTags);
    }

    /**
     * @return bool
     */
    public function hasDistricts(): bool
    {
        return 0 !== count($this->districts);
    }

    /**
     * @return array
     */
    public function getTagIds(): array
    {
        return $this->getIds($this->tags);
    }

    /**
     * @return array
     */
    public function getExchangeTagIds(): array
    {
        return $this->getIds($this->exchangeTags);
    }

    /**
     * @return array
     */
    public function getDistrictIds(): array
    {
        return $this->getIds($this->districts);
    }

    /**
     * @param array $instances
     *
     * @return array
     */
    private function getIds(array $instances): array
    {
        return array_map(function (AbstractTimestampableEntity $instance) {
            return $instance->getId();
        }, $instances);
    }
}
