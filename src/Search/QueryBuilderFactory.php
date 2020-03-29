<?php
/**
 * @license MIT
 */

namespace App\Search;

use Doctrine\ORM\EntityManagerInterface;

class QueryBuilderFactory
{
    /**
     * @var array
     */
    private $mapping = [
        OfferCriteria::class => OfferQueryBuilder::class
    ];

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function create(AbstractCriteria $criteria)
    {
        $class = get_class($criteria);
        if (!isset($this->mapping[$class])) {
            throw new \Exception(sprintf('Unknown crieria %s', $class));
        }

        /** @var QueryBuilderInterface $factoryMethod */
        $factoryMethod = new $this->mapping[$class](
            $this->entityManager->createQueryBuilder(),
            $criteria
        );

        return $factoryMethod->buildQuery();
    }
}
