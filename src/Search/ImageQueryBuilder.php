<?php /** @noinspection PhpMissingParentConstructorInspection */

/**
 * @license MIT
 */

namespace App\Search;

use App\Entity\Image;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\QueryBuilder;

/**
 * Class ImageQueryBuilder
 * @package App\Search
 */
class ImageQueryBuilder extends AbstractQueryBuilder
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
     * @param QueryBuilder  $builder
     * @param ImageCriteria $criteria
     */
    public function __construct(QueryBuilder $builder, ImageCriteria $criteria)
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
            ->addSelect('i')
            ->from(Image::class, 'i');

        if ($this->criteria->offerIds) {
            $this->builder
                ->join('i.offers', 'o')
                ->andWhere('o.id in (:offerIds)')
                ->setParameter(':offerIds', $this->criteria->offerIds, Connection::PARAM_INT_ARRAY);
        }

        return $this->builder;
    }
}
