<?php
/**
 * @license MIT
 */

namespace App\Service;

use App\Repository\ImageRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class ImagesListingService
 */
class ImagesListingService
{
    public const IMAGES_PER_PAGE = 2;

    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var ImageRepository
     */
    private $imageRepository;

    /**
     * ImagesListingService constructor.
     * @param PaginatorInterface $paginator
     * @param ImageRepository    $imageRepository
     */
    public function __construct(PaginatorInterface $paginator, ImageRepository $imageRepository)
    {
        $this->paginator = $paginator;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param string   $offerId
     * @param int|null $page
     *
     * @return PaginationInterface
     */
    public function getOfferImagesListing(string $offerId, ?int $page = 1): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->imageRepository->queryOfferImages($offerId),
            $page,
            100
        );
    }
}
