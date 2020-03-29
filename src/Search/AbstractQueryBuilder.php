<?php
/**
 * @license MIT
 */

namespace App\Search;

use Doctrine\ORM\QueryBuilder;

/**
 * Class OfferQueryBuilder
 * @package App\Search
 */
abstract class AbstractQueryBuilder
{
    /**
     * @var QueryBuilder
     */
    protected $builder;

    /**
     * @var OfferCriteria
     */
    protected $criteria;

    /**
     * OfferQueryBuilder constructor.
     * @param QueryBuilder     $builder
     * @param AbstractCriteria $criteria
     */
    public function __construct(QueryBuilder $builder, AbstractCriteria $criteria)
    {
        $this->criteria = $criteria;
        $this->builder = $builder;
    }

    /**
     * @return QueryBuilder
     */
    abstract public function buildQuery(): QueryBuilder;
}
