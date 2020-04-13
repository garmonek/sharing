<?php

namespace App\Controller;

use App\Entity\ExchangeRequest;
use App\Entity\Offer;
use App\Form\ExchangeRequestType;
use App\Repository\ExchangeRequestRepository;
use App\Search\ExchangeRequestCriteria;
use App\Search\ExchangeRequestCriteriaType;
use App\Search\OfferCriteria;
use App\Search\SearchService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Tetranz\Select2EntityBundle\Service\AutocompleteService;

/**
 * @Route("/exchange/request")
 */
class ExchangeRequestController extends AbstractController
{
    /**
     * @Route("/", name="exchange_request_index", methods={"GET"})
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
        $criteria = new ExchangeRequestCriteria();
        $form = $this->createForm(ExchangeRequestCriteriaType::class, $criteria);

        $form->handleRequest($request);
        $criteria->userId = $this->getUser()->getId();

        $exchangeRequests = $searchService->search($criteria, $request);

        return $this->render('exchange_request/index.html.twig', [
            'exchange_requests' => $exchangeRequests,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/tag/autocomplete", name="exchange_request_tag_autocomplete", methods={"GET"})
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
            $autocomplete->getAutocompleteResults($request, ExchangeRequestCriteriaType::class)
        );
    }

    /**
     * @Route("/{id}", name="exchange_request_show", methods={"GET"})
     *
     * @param ExchangeRequest $exchangeRequest
     *
     * @return Response
     */
    public function show(ExchangeRequest $exchangeRequest): Response
    {
        return $this->render('exchange_request/show.html.twig', [
            'exchange_request' => $exchangeRequest,
        ]);
    }

    /**
     * @Route("/new/target/{id}", name="exchange_request_upsert", methods={"GET","POST"})
     *
     * @param Offer                     $offer
     * @param Request                   $request
     * @param SearchService             $searchService
     * @param ExchangeRequestRepository $requestRepository
     *
     * @return Response
     *
     * @throws Exception
     *
     * @noinspection PhpParamsInspection
     */
    public function upsertExchangeRequest(Offer $offer, Request $request, SearchService $searchService, ExchangeRequestRepository $requestRepository): Response
    {
        $user = $this->getUser();

        if ($user->getId() === $offer->getUserId()) {
            //todo trans
            $this->addFlash('danger', 'error.you_cant_request_exchange_for_your_offer');
        }

        $exchangeRequest = $requestRepository->findOneBy(['user' => $user, 'target' => $offer])
            ?? new ExchangeRequest();

        $criteria = new OfferCriteria();
        $criteria->tags = $offer->getExchangeTags()->toArray();
        $criteria->exchangeTags = $offer->getTags()->toArray();
        $criteria->userId = $user->getId();
        $criteria->active = OfferCriteria::OFFER_ACTIVE;
        $exchangeOffers = $searchService->search($criteria, $request);

        $form = $this->createForm(
            ExchangeRequestType::class,
            $exchangeRequest,
            [
                'matchingOffers' => $exchangeOffers,
            ]
        );

        $form->handleRequest($request);

        $exchangeRequest->setTarget($offer);
        $exchangeRequest->setUser($user);

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

//    /**
//     * @Route(
//     *     "/add/proposal",
//     *      name="exchange_request_add_proposal",
//     *      methods={"POST"}
//      *  )
//     */
//    public function addProposal(Request $request, ExchangeRequestRepository $requestRepository, OfferRepository $offerRepository): Response
//    {
//        $exchange = $requestRepository->find($request->request->getInt('id', 0)) ?? new ExchangeRequest();
//        $proposal = $offerRepository->find($request->request->getInt('proposalId', 0));
//        $target = $offerRepository->find($request->request->getInt('targetId', 0));
//
//        dd($exchange);
//
//        if (! $target) {
//            //todo translate and return
//            $this->addFlash('danger', 'Target offer data is missing');
//            return $this->redirect($request->headers->get('referer'));
//        }
//
//        if (! $proposal) {
//            //todo translate and return
//            $this->addFlash('danger', 'Offer data for proposal is missing');
//            return $this->redirect($request->headers->get('referer'));
//        }
//
//        if (5 < $exchange->getProposals()->count()) {
//            //todo translate and return
//            $this->addFlash('danger', 'You can propose up to 5 offers for exchange');
//            return $this->redirect($request->headers->get('referer'));
//        }
//
//        $user = $this->getUser();
//        if (! $user) {
//            $this->addFlash('danger', 'Only logged user can request exchange');
//            return $this->redirect($request->headers->get('referer'));
//        }
//
//        $exchange->setUser($user);
//        $exchange->addProposal($proposal);
//        $exchange->setTarget($target);
//        $em = $this->getDoctrine()->getManager();
//
//        $em->persist($exchange);
//        $em->flush();
//
//        //todo translate
//        $this->addFlash('success', 'Exchange requested successfully!');
//
//        //todo remove
//        return $this->redirectToRoute('exchange_request_edit', ['id' => $exchange->getId()]);
//    }

    /**
     * @Route("/{id}/edit", name="exchange_request_edit", methods={"GET","POST"})
     *
     * @param Request         $request
     * @param ExchangeRequest $exchangeRequest
     *
     * @return Response
     */
    public function edit(Request $request, ExchangeRequest $exchangeRequest): Response
    {
        //todo add prevent from adding to many requests per target&user same in createNew
        $form = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('exchange_request_index');
        }

        return $this->render('exchange_request/edit.html.twig', [
            'exchange_request' => $exchangeRequest,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="exchange_request_delete", methods={"DELETE"})
     *
     * @param Request         $request
     * @param ExchangeRequest $exchangeRequest
     *
     * @return Response
     */
    public function delete(Request $request, ExchangeRequest $exchangeRequest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$exchangeRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($exchangeRequest);
            $entityManager->flush();
        }

        return $this->redirectToRoute('exchange_request_index');
    }
}
