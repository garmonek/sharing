<?php

namespace App\Search;

use Doctrine\ORM\QueryBuilder;

interface QueryBuilderInterface
{
    public function buildQuery(): QueryBuilder;
}
