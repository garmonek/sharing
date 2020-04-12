<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\ExchangeRequest;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ExchangeRequestQueryBuilder
 *
 */
class ExchangeRequestQueryBuilder extends AbstractQueryBuilder
{
    /**
     * @var ExchangeRequestCriteria
     */
    protected $criteria;

    /**
     * @return QueryBuilder
     */
    public function buildQuery(): QueryBuilder
    {
        $this->builder->select('e')->from(ExchangeRequest::class, 'e');
        $this->builder->andWhere('e.user = :userId')
            ->setParameter(':userId', $this->criteria->userId);

        $filterUsing = ExchangeRequestCriteria::FILTER_USING_PROPOSALS === $this->criteria->filterUsing ?
            'proposals' : 'target';
        $this->builder->join('e.'.$filterUsing, 'offer');

        if ($this->criteria->tags) {
            $tags = $this->criteria->useExchangeTags ? 'exchangeTags' : 'tags';
            $this->builder
                ->join('offer.'.$tags, 't')
                ->andWhere('t.id in (:ids)')
                ->setParameter(':ids', $this->criteria->getTagIds(), Connection::PARAM_INT_ARRAY);
        }

        $cityId = $this->criteria->getCityId();
        if ($cityId) {
            $this->builder->join('offer.district', 'district')
            ->andWhere('district.city = :cityId')
            ->setParameter(':cityId', $cityId, ParameterType::INTEGER);
        }

        return $this->builder;
    }
}
