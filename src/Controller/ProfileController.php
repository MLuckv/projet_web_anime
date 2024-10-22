<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearch;
use App\Repository\UsersRepository;


#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{

    private UsersRepository $UsersRepository;

    public function __construct(UsersRepository $UsersRepository)
    {
        $this->UsersRepository = $UsersRepository;
    }

    #[Route('/popup', name: 'popup')]
    public function popup(Request $request):Response
    {

        $form = $this->createForm(UserSearch::class);

        $form->handleRequest($request);

        // Formulaire valide
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $q = strtolower($data['Username']);

            $user = $this->UsersRepository->createQueryBuilder('u')
            ->where('LOWER(u.Username) = :Username')
            ->setParameter('Username', $q)
                ->setMaxResults(1)
            ->getQuery()
            ->getResult();

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
        }

        //dd($user, $session);

        return $this->render('profile/index.html.twig', [
            'id' => $id,
            'user' => $user,
        ]);
    }
}
