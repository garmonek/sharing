<?php
/**
 * @license MIT
 */

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\AdminUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Search\OfferCriteria;
use App\Search\OfferCriteriaType;
use App\Search\SearchService;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UserController
 */
class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/", name="user_index", methods={"GET"})
     *
     * @param Request            $request
     * @param UserRepository     $userRepository
     * @param PaginatorInterface $paginator
     *
     * @return Response
     */
    public function index(Request $request, UserRepository $userRepository, SearchService $searchService): Response
    {
        $users = $searchService->searchByQuery($userRepository->createQueryBuilder('u'), $request);
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/user/{id}/offer/index", name="user_offer_index", methods={"GET"})
     *
     * @param Request       $request
     * @param User          $user
     * @param SearchService $searchService
     *
     * @return Response
     *
     * @throws Exception
     */
    public function userOfferIndex(Request $request, User $user, SearchService $searchService): Response
    {
        $criteria = new OfferCriteria();
        $searchForm = $this->createForm(OfferCriteriaType::class, $criteria);
        $searchForm->handleRequest($request);

        $criteria->userId = $user->getId();
        /** @noinspection PhpUnhandledExceptionInspection */
        $offers = $searchService->search($criteria, $request);

        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
            'search_form' => $searchForm->createView(),
        ]);
    }

    /**
     * @Route("/user/new", name="user_new", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();

        $formType = $this->isGranted(USER::ROLE_ADMIN) ? AdminUserType::class : UserType::class;
        $form = $this->createForm($formType, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = 'message.user_added';
            if ($this->isGranted(USER::ROLE_ADMIN) && true === $form->get('isAdmin')->getData()) {
                $role = new Role();
                $role->setRole(USER::ROLE_ADMIN);
                $role->setUser($user);
                $entityManager->persist($role);

                $message = 'message.admin_added';
            }

            $this->addFlash('success', $message);

            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('security_login');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_show", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function show(User $user): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (!$this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirect('/');
            }
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="user_edit", methods={"GET","POST"})
     *
     * @param Request                      $request
     * @param User                         $user
     * @param UserPasswordEncoderInterface $encoder
     *
     * @return Response
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $encoder): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (!$this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirect('/');
            }
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $encoder->encodePassword($user, $user->getPassword())
            );
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'message.edited_successfully');

            return $this->redirectToRoute('user_edit', [
                'id' => $user->getId(),
            ]);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/{id}/addAdmin", name="user_addAdmin", methods={"GET"})
     *
     * @param User $user
     *
     * @return Response
     */
    public function addAdmin(User $user): Response
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $role = new Role();
            $role->setRole(USER::ROLE_ADMIN);
            $role->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($role);
            $entityManager->flush();

            $this->addFlash('success', 'message.admin_added');
        }

        return $this->redirectToRoute('user_index', [
            'id' => $user->getId(),
        ]);
    }

    /**
     * @Route("/user/{id}", name="user_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param User    $user
     *
     * @return Response
     */
    public function delete(Request $request, User $user): Response
    {
        if (!$this->isGranted(USER::ROLE_ADMIN)) {
            if (!$this->getUser() || $user->getUsername() !== $this->getUser()->getUsername()) {
                return $this->redirect('/');
            }
        }

        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'message.deleted_successfully');
        }

        return $this->redirectToRoute('user_index');
    }
}
