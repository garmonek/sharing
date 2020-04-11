<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\City;

class ExchangeRequestCriteria extends AbstractCriteria
{
    public const FILTER_USING_TARGET = 1;
    public const FILTER_USING_PROPOSALS = 2;

    public $userId;

    public $filterUsing;

    public $city;

    public $tags;

    public $useExchangeTags;

    public function getCityId(): ?int
    {
        if (!$this->city instanceof City) {
            return null;
        }

        return $this->city->getId();
    }

    public function getTagIds(): array
    {
        return $this->getIds($this->tags);
    }
}
