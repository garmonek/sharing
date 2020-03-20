<?php
/**
 * @license MIT
 */

namespace App\Service;

use App\Repository\DistrictRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class DistrictListingService
 */
class DistrictListingService
{
    public const DISTRICTS_PER_PAGE = 10;

    /**
     * @var DistrictRepository
     */
    private $districtRepository;

    /**
     * @var PaginationInterface
     */
    private $pagination;

    /**
     * DistrictListingService constructor.
     * @param DistrictRepository $districtRepository
     * @param PaginatorInterface $pagination
     */
    public function __construct(DistrictRepository $districtRepository, PaginatorInterface $pagination)
    {
        $this->districtRepository = $districtRepository;
        $this->pagination = $pagination;
    }

    /**
     * @param int         $cityId
     * @param int|null    $page
     * @param string|null $search
     *
     * @return PaginationInterface
     */
    public function getDistrictListing(int $cityId, ?int $page, ?string $search): PaginationInterface
    {
        $page = $page ?? 1;
        $search = $search ?? '';

        return $this->pagination->paginate(
            $this->districtRepository->queryNameLikeForCity($cityId, $search),
            $page,
            self::DISTRICTS_PER_PAGE
        );
    }
}
