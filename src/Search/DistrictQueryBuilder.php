<?php /** @noinspection PhpMissingParentConstructorInspection */

/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\District;
use Doctrine\DBAL\ParameterType;
use Doctrine\ORM\QueryBuilder;

/**
 * Class DistrictQueryBuilder
 *
 */
class DistrictQueryBuilder extends AbstractQueryBuilder
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
     * @param DistrictCriteria $criteria
     */
    public function __construct(QueryBuilder $builder, DistrictCriteria $criteria)
    {
        $this->criteria = $criteria;
        $this->builder = $builder;
    }

    /**
     * @return QueryBuilder
     */
    public function buildQuery(): QueryBuilder
    {
        $this->builder
            ->addSelect('d')
            ->from(District::class, 'd');

        if ($this->criteria->cityId) {
            $this->builder
                ->join('d.city', 'c')
                ->andWhere('c.id = :cityId')
                ->setParameter(':cityId', $this->criteria->cityId, ParameterType::INTEGER);
        }

        if ($this->criteria->search) {
            $this->builder->andWhere('d.name like :val')
                ->setParameter('val', '%'.$this->criteria->search.'%', ParameterType::STRING)
                ->orderBy('d.name', 'ASC');
        }

        return $this->builder;
    }
}
