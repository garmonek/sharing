<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\Offer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;

/**
 * Class OfferQueryBuilder
 * @package App\Search
 */
class OfferQueryBuilder extends AbstractQueryBuilder
{
    /**
     * @return QueryBuilder
     */
    public function buildQuery(): QueryBuilder
    {
        $this->builder
            ->addSelect('o')
            ->from(Offer::class, 'o')
            ->groupBy('o.id')
            ->join('o.district', 'd')
            ->join('o.tags', 't')
            ->join('o.exchangeTags', 'et');

        if ($this->criteria->hasTags()) {
            $this->searchWithTags();
        }

        if ($this->criteria->hasExchangeTags()) {
            $this->searchWithExchangeTags();
        }

        $id = $this->criteria->getCityId();
        if ($id) {
            $this->builder->andWhere('d.cityId = :cityId')
                ->setParameter(':cityId', $id, ParameterType::INTEGER);
        }

        if ($this->criteria->hasDistricts()) {
            $this->searchWithDistrict();
        }

        if ($this->criteria->userId) {
            $this->builder
                ->andWhere('o.userId = :userId')
                ->setParameter(':userId', $this->criteria->userId, ParameterType::INTEGER);
        }

        $this->searchWithActive();
        $this->searchWithOrder();



        return $this->builder;
    }

    /**
     * @return QueryBuilder
     */
    private function searchWithDistrict(): QueryBuilder
    {
        return $this->builder->andWhere('d.id in (:districtIds)')
            ->setParameter(':districtIds', $this->criteria->getDistrictIds(), Connection::PARAM_INT_ARRAY);
    }

    private function searchWithTags(): void
    {
        $this->builder->andwhere('t.id in (:tagIds)');
        $this->builder->setParameter(':tagIds', $this->criteria->getTagIds(), Connection::PARAM_INT_ARRAY);
    }

    private function searchWithExchangeTags(): void
    {
        $this->builder->andwhere('et.id in (:exchangeTagIds)');
        $this->builder->setParameter(':exchangeTagIds', $this->criteria->getExchangeTagIds(), Connection::PARAM_INT_ARRAY);
    }

    private function searchWithActive(): void
    {
        switch ($this->criteria->active) {
            case OfferCriteria::OFFER_INACTIVE:
                $this->builder->andWhere('o.active = :active');
                $this->builder->setParameter(':active', false, ParameterType::BOOLEAN);
                break;
            case OfferCriteria::OFFER_ALL:
                break;
            default:
                $this->builder->andWhere('o.active = :active');
                $this->builder->setParameter(':active', true, ParameterType::BOOLEAN);
        }
    }

    private function searchWithOrder(): void
    {
        $sortDirection = OfferCriteria::SORT_DIRECTION_ASC === $this->criteria->sortDirection ? 'ASC' : 'DESC';
        switch ($this->criteria->sortValue) {
            case OfferCriteria::SORT_VALUE_CREATED:
                $this->builder->orderBy('o.createdAt', $sortDirection);
                break;
            case OfferCriteria::SORT_VALUE_UPDATED:
                $this->builder->orderBy('o.updatedAt', $sortDirection);
                break;
            default:
                $this->builder->orderBy('o.updatedAt', $sortDirection);
        }

        switch ($this->criteria->sortValue) {
            case OfferCriteria::SORT_VALUE_TAGS && $this->criteria->hasTags():
                $this->builder->orderBy('COUNT(t.id)', $sortDirection);
                break;
            case OfferCriteria::SORT_VALUE_EXCHANGE_TAGS && $this->criteria->hasExchangeTags():
                $this->builder->orderBy('COUNT(et.id)', $sortDirection);
                break;
            default:
                if ($this->criteria->hasTags() && !$this->criteria->hasExchangeTags()) {
                    $this->builder->orderBy('COUNT(t.id)', 'DESC');
                }

                if ($this->criteria->hasExchangeTags() && !$this->criteria->hasTags()) {
                    $this->builder
                        ->orderBy('COUNT(et.id)', 'DESC');
                }

                if ($this->criteria->hasTags() && $this->criteria->hasExchangeTags()) {
                    $this->builder
                        ->addOrderBy('COUNT(t.id)', 'DESC')
                        ->addOrderBy('COUNT(et.id)', 'DESC');
                }
        }
    }
}
