<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\UserSearch;
use App\Repository\UsersRepository;
use App\Repository\RateRepository;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

#[Route('/profile', name: 'profile_')]
class ProfileController extends AbstractController
{
    private UsersRepository $UsersRepository;
    private RateRepository $RateRepository;
    private AnimeRepository $AnimeRepository;

    public function __construct(UsersRepository $UsersRepository, RateRepository $RateRepository, AnimeRepository $AnimeRepository)
    {
        $this->UsersRepository = $UsersRepository;
        $this->RateRepository = $RateRepository;
        $this->AnimeRepository = $AnimeRepository;
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

            // Redirection vers la page des détails de l'utilisateur avec le slug id
            return $this->redirectToRoute('profile_index', ['id' => $userId]);
        }

        return $this->render('profile/popup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/index', name: 'index')]
    public function index(Request $request, string $id):Response
    {
        // Récupérer la session
        $session = $request->getSession();
        $user = $session->get($id);

        // Si l'utilisateur n'existe pas dans la session, renvoyer une erreur 404
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        } else {
            if (!$user instanceof \App\Entity\User) {
                throw new \LogicException('Invalid user entity');
            }

            // Récupérer les informations de l'utilisateur
            $age = $this->UsersRepository->getUserAge($user);
            $rates = $this->RateRepository->findByUserId($user->getId());

            // Graphiques
            $graph1 = $this->AnimeRepository->countAnimesByGenreForUser($user);
            $graph1_label = [];
            $graph1_data = [];
            $graph2 = $this->AnimeRepository->averageRatingByGenreForUser($user);
            $graph2_label = [];
            $graph2_data = [];

            foreach ($graph1 as $data) {
                $graph1_label[] = $data['genre'];
                $graph1_data[] = $data['count'];
            }

            foreach ($graph2 as $data) {
                $graph2_label[] = $data['genre'];
                $graph2_data[] = $data['averageRating'];
            }

            // test de process
            $recommendations = $this->getAnimeRecommendations($user->getId(), 5);
        }

        return $this->render('profile/index.html.twig', [
            'id' => $id,
            'user' => $user,
            'age' => $age,
            'nbRate' => count($rates),
            'graph1_data' => json_encode($graph1_data),
            'graph1_label' => json_encode($graph1_label),
            'graph2_data' => json_encode($graph2_data),
            'graph2_label' => json_encode($graph2_label),
            'recommendations' => $recommendations,
        ]);
    }

    private function getAnimeRecommendations(int $userId, int $n): array
    {
        $pythonPath = 'C:\Users\vmoul\anaconda3\python.exe';
        $scriptPath = 'C:\wamp64\www\mv\projet_web_anime\algoRecomandation\algoReco.py';

//        $pythonPath = 'C:\Users\ayoub\anaconda3\python.exe';
//        $scriptPath = 'C:\Users\ayoub\Documents\GitHub\projet_web_anime\algoRecoma\algoReco.py';

        // Processus Python
        $process = new Process([
            $pythonPath,
            $scriptPath,
            $userId,  // Passer l'utilisateur
            $n,       // Nombre de recommandations
            0.5       // Facteur d'échantillonnage
        ]);

        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = json_decode($process->getOutput(), true);

        // Obtenir les IDs des animes recommandés
        $recommendedAnimeIds = $output['recommendations'] ?? [];

        // Récupérer les informations des animes recommandés
        $recommendedAnimeNames = [];
        foreach ($this->AnimeRepository->findBy(['id' => $recommendedAnimeIds]) as $anime) {
            $recommendedAnimeNames[] = [
                'nom' => $anime->getEnglishName() ?? $anime->getNom(),
                'image' => $anime->getImageUrl(),
            ];
        }

        return $recommendedAnimeNames;
    }



}
