<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\District;
use App\Entity\Tag;

/**
 * Class Criteria
 */
class OfferCriteria extends AbstractCriteria
{
    public const SORT_DIRECTION_ASC = 1;
    public const SORT_DIRECTION_DESC = 2;

    public const OFFER_ACTIVE = 0;
    public const OFFER_INACTIVE = 1;
    public const OFFER_ALL = 2;

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
     * @var int
     */
    public $limit = 10;

    public function hasTags(): bool
    {
        return 0 !== count($this->tags);
    }

    public function hasExchangeTags(): bool
    {
        return 0 !== count($this->exchangeTags);
    }

    public function hasDistricts(): bool
    {
        return 0 !== count($this->districts);
    }

    public function getTagIds(): array
    {
        return $this->getIds($this->tags);
    }

    public function getExchangeTagIds(): array
    {
        return $this->getIds($this->exchangeTags);
    }

    public function getDistrictIds(): array
    {
        return $this->getIds($this->districts);
    }

    /**
     * @return array
     */
    private function getIds(array $instances): array
    {
        return array_map(function ($instance) {
            return $instance->getId();
        }, $instances);
    }
}
