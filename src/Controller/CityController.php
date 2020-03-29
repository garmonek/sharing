<?php
/**
 * @license MIT
 */

namespace App\Controller;

use App\Entity\City;
use App\Entity\District;
use App\Form\CityType;
use App\Repository\CityRepository;
use App\Search\DistrictCriteria;
use App\Search\SearchService;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tetranz\Select2EntityBundle\Service\AutocompleteService;

/**
 * @Route("/admin/city")
 */
class CityController extends AbstractController
{
    /**
     * @Route("/", name="city_index", methods={"GET"})
     *
     * @param CityRepository $cityRepository
     *
     * @return Response
     */
    public function index(CityRepository $cityRepository): Response
    {
        return $this->render('city/index.html.twig', [
            'cities' => $cityRepository->findAll(),
        ]);
    }

    /**
     * @Route("/json_districts", name="json_city_districts", methods={"GET"})
     *
     * @param Request             $request
     * @param AutocompleteService $autocomplete
     *
     * @return JsonResponse
     */
    public function jsonCityDistricts(Request $request, AutocompleteService $autocomplete): JsonResponse
    {
        return new JsonResponse(
            $autocomplete->getAutocompleteResults($request, CityType::class)
        );
    }

    /**
     * @Route("/new", name="city_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $city = new City();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($city);
            $entityManager->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/new.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="city_show", methods={"GET"})
     *
     * @param City          $city
     *
     * @param Request       $request
     *
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function show(City $city, Request $request, SearchService $searchService): Response
    {
        return $this->render('city/show.html.twig', [
            'city' => $city,
            'districts' => $this->getDistrictPagination($city, $request, $searchService),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="city_edit", methods={"GET","POST"})
     *
     * @param Request       $request
     * @param City          $city
     *
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function edit(Request $request, City $city, SearchService $searchService): Response
    {
        $districts = $city->getDistricts()->getValues();
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($districts as $district) {
                $city->addDistrict($district);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('city_index');
        }

        return $this->render('city/edit.html.twig', [
            'city' => $city,
            'form' => $form->createView(),
            'districts' => $this->getDistrictPagination($city, $request, $searchService),
        ]);
    }

    /**
     * @Route("/{id}", name="city_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param City    $city
     *
     * @return Response
     */
    public function delete(Request $request, City $city): Response
    {
        if ($this->isCsrfTokenValid('delete'.$city->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($city);
            $entityManager->flush();
        }

        return $this->redirectToRoute('city_index');
    }

    /**
     * @Route("/district/{id}", name="district_delete", methods={"DELETE"})
     *
     * @param Request  $request
     * @param District $district
     *
     * @return Response
     */
    public function districtDelete(Request $request, District $district): Response
    {
        if ($this->isCsrfTokenValid('delete'.$district->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($district);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param City          $city
     * @param Request       $request
     * @param SearchService $searchService
     *
     * @return PaginationInterface
     *
     * @throws Exception
     */
    private function getDistrictPagination(City $city, Request $request, SearchService $searchService): PaginationInterface
    {
        $criteria = new DistrictCriteria();
        $criteria->cityId = $city->getId();
        $criteria->search = $request->get('search', null);

        return $searchService->search($criteria, $request);
    }
}
