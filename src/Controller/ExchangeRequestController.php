<?php

namespace App\Controller;

use App\Entity\ExchangeRequest;
use App\Form\ExchangeRequestType;
use App\Repository\ExchangeRequestRepository;
use App\Repository\OfferRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/exchange/request")
 */
class ExchangeRequestController extends AbstractController
{
    /**
     * @Route("/", name="exchange_request_index", methods={"GET"})
     */
    public function index(ExchangeRequestRepository $exchangeRequestRepository): Response
    {
        return $this->render('exchange_request/index.html.twig', [
            'exchange_requests' => $exchangeRequestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="exchange_request_show", methods={"GET"})
     */
    public function show(ExchangeRequest $exchangeRequest): Response
    {
        return $this->render('exchange_request/show.html.twig', [
            'exchange_request' => $exchangeRequest,
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
//            //todo translate and retirm
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
     */
    public function edit(Request $request, ExchangeRequest $exchangeRequest): Response
    {
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
