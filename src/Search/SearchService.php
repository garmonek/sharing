<?php
/**
 * @license MIT
 */

namespace App\Search;

use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchService
 */
class SearchService
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var QueryBuilderFactory
     */
    private $builderFactory;

    /**
     * SearchService constructor.
     * @param PaginatorInterface  $paginator
     * @param QueryBuilderFactory $builderFactory
     */
    public function __construct(PaginatorInterface $paginator, QueryBuilderFactory $builderFactory)
    {
        $this->paginator = $paginator;
        $this->builderFactory = $builderFactory;
    }

    /**
     * @param AbstractCriteria $criteria
     * @param Request          $request
     *
     * @return PaginationInterface
     *
     * @throws Exception
     */
    public function search(AbstractCriteria $criteria, Request $request)
    {
        /** @noinspection PhpUnhandledExceptionInspection */

        return $this->paginator->paginate(
            $this->builderFactory->create($criteria),
            $request->get('page', 1),
            $criteria->limit
        );
    }
}
