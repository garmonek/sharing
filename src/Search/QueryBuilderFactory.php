<?php
/**
 * @license MIT
 */

namespace App\Search;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Exception;

/**
 * Class QueryBuilderFactory
 * @package App\Search
 */
class QueryBuilderFactory
{
    /**
     * @var array
     */
    private $mapping = [
        OfferCriteria::class => OfferQueryBuilder::class,
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * QueryBuilderFactory constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /** @noinspection PhpUnhandledExceptionInspection */
    /**
     * @param AbstractCriteria $criteria
     *
     * @return QueryBuilder
     *
     * @throws Exception
     */
    public function create(AbstractCriteria $criteria)
    {
        $class = get_class($criteria);
        if (!isset($this->mapping[$class])) {
            throw new Exception(sprintf('Unknown criteria %s', $class));
        }

        /** @var QueryBuilderInterface $factoryMethod */
        $factoryMethod = new $this->mapping[$class](
            $this->entityManager->createQueryBuilder(),
            $criteria
        );

        return $factoryMethod->buildQuery();
    }
}
