<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\AbstractTimestampableEntity;

/**
 * Class AbstractCriteria
 *
 */
abstract class AbstractCriteria
{
    /**
     * @var int
     */
    public $limit = 10;

    /**
     * @param array $instances
     *
     * @return array
     */
    protected function getIds(array $instances): array
    {
        return array_map(function (AbstractTimestampableEntity $instance) {
            return $instance->getId();
        }, $instances);
    }
}
