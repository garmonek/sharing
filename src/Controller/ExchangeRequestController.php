<?php

namespace App\Controller;

use App\Entity\ExchangeRequest;
use App\Form\ExchangeRequestType;
use App\Repository\ExchangeRequestRepository;
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
     *
     * @param ExchangeRequestRepository $exchangeRequestRepository
     *
     * @return Response
     */
    public function index(ExchangeRequestRepository $exchangeRequestRepository): Response
    {
        return $this->render('exchange_request/index.html.twig', [
            'exchange_requests' => $exchangeRequestRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="exchange_request_new", methods={"GET","POST"})
     *
     * @param Request $request
     *
     * @return Response
     */
    public function new(Request $request): Response
    {
        $exchangeRequest = new ExchangeRequest();
        $form = $this->createForm(ExchangeRequestType::class, $exchangeRequest);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($exchangeRequest);
            $entityManager->flush();

            return $this->redirectToRoute('exchange_request_index');
        }

        return $this->render('exchange_request/new.html.twig', [
            'exchange_request' => $exchangeRequest,
            'form' => $form->createView(),
        ]);
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
     * @Route("/{id}/edit", name="exchange_request_edit", methods={"GET","POST"})
     *
     * @param Request         $request
     * @param ExchangeRequest $exchangeRequest
     *
     * @return Response
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
