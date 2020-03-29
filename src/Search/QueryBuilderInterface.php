<?php

namespace App\Search;

use Doctrine\ORM\QueryBuilder;

/**
 * Interface QueryBuilderInterface
 * @package App\Search
 */
interface QueryBuilderInterface
{
    /**
     * @return QueryBuilder
     */
    public function buildQuery(): QueryBuilder;
}
