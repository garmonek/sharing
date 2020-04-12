<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\City;

/**
 * Class ExchangeRequestCriteria
 *
 */
class ExchangeRequestCriteria extends AbstractCriteria
{
    public const FILTER_USING_TARGET = 1;
    public const FILTER_USING_PROPOSALS = 2;

    /**
     * @var
     */
    public $userId;

    /**
     * @var
     */
    public $filterUsing;

    /**
     * @var
     */
    public $city;

    /**
     * @var
     */
    public $tags;

    /**
     * @var
     */
    public $useExchangeTags;

    /**
     * @return int|null
     */
    public function getCityId(): ?int
    {
        if (!$this->city instanceof City) {
            return null;
        }

        return $this->city->getId();
    }

    /**
     * @return array
     */
    public function getTagIds(): array
    {
        return $this->getIds($this->tags);
    }
}
