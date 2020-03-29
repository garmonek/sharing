<?php
/**
 * @license MIT
 */

namespace App\Search;

/**
 * Class DistrictCriteria
 * @package App\Search
 */
class DistrictCriteria extends AbstractCriteria
{
    /**
     * @var ?int
     */
    public $cityId = null;

    /**
     * @var ?string
     */
    public $search = null;
}
