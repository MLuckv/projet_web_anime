<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

#[Route('/algorithm', name: 'algorithm_')]
class AlgorithmController extends AbstractController
{
    private AnimeRepository $AnimeRepository;

    public function __construct(AnimeRepository $AnimeRepository)
    {
        $this->AnimeRepository = $AnimeRepository;
    }

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        $genres = $this->AnimeRepository->findUniqueGenres();

        return $this->render('algorithm/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/recommend', name: 'recommend', methods: ['POST'])]
    public function recommend(Request $request): JsonResponse
    {
        // Récupérer le contenu JSON de la requête
        $data = json_decode($request->getContent(), true);

        // Vérifier que les genres sont présents et bien un tableau
        if (!isset($data['genres']) || !is_array($data['genres'])) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Invalid input format'
            ], Response::HTTP_BAD_REQUEST);
        }

        $selectedGenres = $data['genres'];

        // Exemple de traitement : Afficher les genres reçus dans la réponse JSON
        return new JsonResponse([
            'success' => true,
            'selectedGenres' => $selectedGenres,
            // Ajoutez des recommandations ici si nécessaire
        ]);
    }
}
