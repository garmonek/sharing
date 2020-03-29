<?php
/**
 * @license MIT
 */

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use App\Search\OfferCriteriaType;
use App\Service\OfferDistrictAutocomplete;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Tetranz\Select2EntityBundle\Service\AutocompleteService;

/**
 * @Route("/search")
 */
class SearchController
{
    /**
     * @Route("/district/autocomplete", name="search_district_autocomplete", methods={"GET"})
     *
     * @param Request                   $request
     * @param OfferDistrictAutocomplete $autocomplete
     *
     * @return JsonResponse
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function districtAutocomplete(Request $request, OfferDistrictAutocomplete $autocomplete): JsonResponse
    {
        return new JsonResponse(
            $autocomplete->getAutocompleteResults($request, OfferCriteriaType::class)
        );
    }

    /**
     * @Route("/tag/autocomplete", name="search_tag_autocomplete", methods={"GET"})
     *
     * @param Request             $request
     * @param AutocompleteService $autocomplete
     *
     * @return JsonResponse
     *
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function tagAutocomplete(Request $request, AutocompleteService $autocomplete): JsonResponse
    {
        return new JsonResponse(
            $autocomplete->getAutocompleteResults($request, OfferCriteriaType::class)
        );
    }
}
