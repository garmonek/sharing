<?php
/**
 * @license MIT
 */

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Offer;
use App\Form\Offer\OfferType;
use App\Form\Offer\OfferEditType;
use App\Form\CriteriaType;
use App\Repository\OfferRepository;
use App\Search\Criteria;
use App\Search\SearchService;
use App\Service\ImagesListingService;
use App\Service\OfferDistrictAutocomplete;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tetranz\Select2EntityBundle\Service\AutocompleteService;

/**
 * @Route("/offer")
 */
class OfferController extends AbstractController
{
    /**
     * @var ImagesListingService
     */
    private $imagesListing;

    /**
     * OfferController constructor.
     * @param ImagesListingService $imagesListing
     */
    public function __construct(ImagesListingService $imagesListing)
    {
        $this->imagesListing = $imagesListing;
    }

    /**
     * @Route("/", name="offer_index", methods={"GET"})
     *
     * @param OfferRepository $offerRepository
     *
     * @return Response
     */
    public function index(Request $request, SearchService $searchService): Response
    {
        $criteria = new Criteria();
        $searchForm = $this->createForm(CriteriaType::class, $criteria);
        $searchForm->handleRequest($request);

        $offers = $searchService->search($criteria, $request);

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'search_form' => $searchForm->createView()
        ]);
    }

    /**
     * @Route("/district/autocomplete", name="offer_district_autocomplete", methods={"GET"})
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
            $autocomplete->getAutocompleteResults($request, OfferType::class)
        );
    }

    /**
     * @Route("/tag/autocomplete", name="offer_tag_autocomplete", methods={"GET"})
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
            $autocomplete->getAutocompleteResults($request, OfferType::class)
        );
    }

    /**
     * @Route("/new", name="offer_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($offer);
            $entityManager->persist($offer->getImages());
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_show", methods={"GET"})
     *
     * @param Offer   $offer
     *
     * @param Request $request
     *
     * @return Response
     */
    public function show(Offer $offer, Request $request): Response
    {
        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'images' => $this->makeImagesPagination($offer, $request),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_edit", methods={"GET","POST"})
     *
     * @param Request $request
     * @param Offer   $offer
     *
     * @return Response
     */
    public function edit(Request $request, Offer $offer): Response
    {
        $images = $offer->getImages()->map(function (Image $image){return $image;});
        $form = $this->createForm(OfferEditType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($images as $image) {
                $offer->addImage($image);
            }
            $em->persist($offer);
            $em->flush();

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'images' => $this->makeImagesPagination($offer, $request),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Offer   $offer
     *
     * @return Response
     */
    public function delete(Request $request, Offer $offer): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offer->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($offer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('offer_index');
    }

    /**
     * @Route("/{id}/image/{imageId}", name="offer_image_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param Offer   $offer
     *
     * @param int     $imageId
     *
     * @return Response
     */
    public function imageDelete(Request $request, Offer $offer, int $imageId): Response
    {
        $image = $offer->getImages()->filter(function (Image $image) use ($imageId) {
            return $imageId === $image->getId();
        })->first();

        if ($image
            && $this->isCsrfTokenValid(
                'delete'.$offer->getId().$imageId,
                $request->request->get('_token')
            )
        ) {
            $offer->removeImage($image);
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($offer);
            $manager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @param Offer   $offer
     * @param Request $request
     *
     * @return PaginationInterface
     */
    private function makeImagesPagination(Offer $offer, Request $request): PaginationInterface
    {
        return $this->imagesListing->getOfferImagesListing($offer->getId(), $request->get('page', 1));
    }
}
