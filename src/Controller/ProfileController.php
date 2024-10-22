<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearch;
use App\Repository\UsersRepository;
use App\Repository\RateRepository;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{

    private UsersRepository $UsersRepository;
    private RateRepository $RateRepository;

    public function __construct(UsersRepository $UsersRepository, RateRepository $RateRepository)
    {
        $this->UsersRepository = $UsersRepository;
        $this->RateRepository = $RateRepository;
    }

    #[Route('/popup', name: 'popup')]
    public function popup(Request $request):Response
    {

        $form = $this->createForm(UserSearch::class);

        $form->handleRequest($request);

        // Formulaire valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();


            $user = $this->UsersRepository->findByUsername($data['Username']);

            // id pour le slug
            $userId = uniqid();

            // variable de session
            $session = $request->getSession();
            $session->set($userId, $user);

            //dd($user, $session);
            // Redirection vers la page des détails de l'utilisateur avec le slug id
            return $this->redirectToRoute('profile_index', ['id' => $userId]);
        }

        return $this->render('profile/popup.html.twig', [
            'form' => $form->createView(),
        ]);
    }




    #[Route('/{id}/index', name: 'index')]
    public function index(Request $request, string $id): Response
    {
        // Récupérer la session
        $session = $request->getSession();
        $user = $session->get($id);

        // Si l'utilisateur n'existe pas dans la session, renvoyer une erreur 404
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }else{

            if (!$user instanceof \App\Entity\User) {
                throw new \LogicException('Invalid user entity');
            }

            $age = $this->UsersRepository->getUserAge($user);
            $rates = $this->RateRepository->findByUserId($user->getId());

            //dd(count($rates));
        }

        //dd($user, $session);

        return $this->render('profile/index.html.twig', [
            'id' => $id,
            'user' => $user,
            'age' => $age,
            'nbRate' => count($rates),
        ]);
    }
}
