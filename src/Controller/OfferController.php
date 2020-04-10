<?php
/**
 * @license MIT
 */

namespace App\Controller;

use App\Entity\ExchangeRequest;
use App\Entity\Image;
use App\Entity\Offer;
use App\Form\ExchangeRequestType;
use App\Form\Offer\OfferType;
use App\Form\Offer\OfferEditType;
use App\Search\ImageCriteria;
use App\Search\OfferCriteriaType;
use App\Search\OfferCriteria;
use App\Search\SearchService;
use App\Service\OfferDistrictAutocomplete;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Exception;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
     * @Route("/", name="offer_index", methods={"GET"})
     *
     * @param Request       $request
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function index(Request $request, SearchService $searchService): Response
    {
        $criteria = new OfferCriteria();
        $searchForm = $this->createForm(OfferCriteriaType::class, $criteria);
        $searchForm->handleRequest($request);

        $offers = $searchService->search($criteria, $request);

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'search_form' => $searchForm->createView(),
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
            $entityManager->flush();

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/new.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/exchange/request/new", name="exchange_request_new", methods={"GET","POST"})
     */
    public function newExchangeRequest(Offer $offer, Request $request, SearchService $searchService): Response
    {
        $exchangeRequest = new ExchangeRequest();

        $criteria = new OfferCriteria();
        $criteria->tags = $offer->getExchangeTags()->toArray();
        $criteria->exchangeTags = $offer->getTags()->toArray();
        $criteria->userId = $this->getUser()->getId();
        $criteria->active = OfferCriteria::OFFER_ACTIVE;
        $exchangeOffers = $searchService->search($criteria, $request);

        $form = $this->createForm(
            ExchangeRequestType::class,
            $exchangeRequest,
            [
                'matchingOffers' => $exchangeOffers
            ]
        );

        $form->handleRequest($request);

        $exchangeRequest->setTarget($offer);
        $exchangeRequest->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exchangeRequest);
            $entityManager->flush();

            return $this->redirectToRoute('exchange_request_index');
        }

        return $this->render('exchange_request/new.html.twig', [
            'offer' => $offer,
            'exchange_request' => $exchangeRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="offer_show", methods={"GET"})
     *
     * @param Offer         $offer
     *
     * @param Request       $request
     *
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     *
     * @noinspection PhpUnhandledExceptionInspection
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function show(Offer $offer, Request $request, SearchService $searchService): Response
    {
        $criteria = new OfferCriteria();

        $searchForm = $this->createForm(OfferCriteriaType::class, $criteria, [
            OfferCriteriaType::ENABLE_TAGS => false,
            OfferCriteriaType::ENABLE_EXCHANGE_TAGS => false,
            OfferCriteriaType::ENABLE_ACTIVE => false,
        ]);
        $searchForm->handleRequest($request);

        $criteria->tags = $offer->getExchangeTags()->toArray();
        $criteria->exchangeTags = $offer->getTags()->toArray();
        $criteria->active = OfferCriteria::OFFER_ACTIVE;
        $criteria->userId = $this->getUser()->getId();
        $exchangeOffers = $searchService->search($criteria, $request);

        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'images' => $this->getImagesPagination($offer, $request, $searchService),
            'search_form' => $searchForm->createView(),
            'offers' => $exchangeOffers,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="offer_edit", methods={"GET","POST"})
     *
     * @param Request       $request
     * @param Offer         $offer
     *
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     *
     * @noinspection PhpUnhandledExceptionInspection
     */
    public function edit(Request $request, Offer $offer, SearchService $searchService): Response
    {
        $images = $offer->getImages()->map(function (Image $image) {
            return $image;
        });
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
            'images' => $this->getImagesPagination($offer, $request, $searchService),
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
     * @param Offer         $offer
     * @param Request       $request
     * @param SearchService $searchService
     *
     * @return PaginationInterface
     *
     * @throws Exception
     */
    private function getImagesPagination(Offer $offer, Request $request, SearchService $searchService): PaginationInterface
    {
        $imageCriteria = new ImageCriteria();
        $imageCriteria->offerIds = [$offer->getId()];

        return $searchService->search($imageCriteria, $request);
    }
}
