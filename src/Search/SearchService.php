<?php
/**
 * @license MIT
 */

namespace App\Search;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
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

    public function __construct(PaginatorInterface $paginator, QueryBuilderFactory $builderFactory)
    {
        $this->paginator = $paginator;
        $this->builderFactory = $builderFactory;
    }

    public function search(AbstractCriteria $criteria, Request $request)
    {
        return $this->paginator->paginate(
            $this->builderFactory->create($criteria),
            $request->get('page', 1),
            $criteria->limit
        );
    }
}
