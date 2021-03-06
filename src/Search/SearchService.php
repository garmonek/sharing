<?php
/**
 * @license MIT
 */

namespace App\Search;

use Doctrine\ORM\QueryBuilder;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\Paginator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SearchService
 */
class SearchService
{
    /**
     * @var int
     */
    private $paginationCount = 1;

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

    public function searchByQuery(QueryBuilder $builder, Request $request, int $limit = 10): PaginationInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $pageParameterName = $this->createPageParameterName();
        $pagination = $this->paginator->paginate(
            $builder,
            $request->get($pageParameterName, 1),
            $limit
        );

        $pagination->setPaginatorOptions([
            Paginator::PAGE_PARAMETER_NAME => $pageParameterName,
        ]);

        return $pagination;
    }

    /**
     * @return PaginationInterface
     */
    public function createEmptyPagination(): PaginationInterface
    {
        $pagination = $this->paginator->paginate([], 1, 1);
        $pageParameterName = $this->createPageParameterName();
        $pagination->setPaginatorOptions([
            Paginator::PAGE_PARAMETER_NAME => $pageParameterName,
        ]);

        return $pagination;
    }

    /**
     * @param AbstractCriteria $criteria
     * @param Request          $request
     *
     * @return PaginationInterface
     *
     * @throws Exception
     */
    public function search(AbstractCriteria $criteria, Request $request): PaginationInterface
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        $pageParameterName = $this->createPageParameterName();
        $pagination = $this->paginator->paginate(
            $this->builderFactory->createQuery($criteria),
            $request->get($pageParameterName, 1),
            $criteria->limit
        );

        $pagination->setPaginatorOptions([
            Paginator::PAGE_PARAMETER_NAME => $pageParameterName,
        ]);

        return $pagination;
    }

    /**
     * @return string
     */
    private function createPageParameterName(): string
    {
        $name = 'p'.$this->paginationCount.'page';
        $this->paginationCount++;

        return $name;
    }
}
