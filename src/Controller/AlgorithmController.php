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
        // Récupérer les genres avec leurs animes associés
        $genres = $this->AnimeRepository->findGenreWithAnime();

        return $this->render('algorithm/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/recommend', name: 'recommend', methods: ['POST'])]
    public function recommend(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Vérifier que les genres et les animes sont présents
        if (!isset($data['genres']) || !is_array($data['genres']) || !isset($data['animes']) || !is_array($data['animes'])) {
            return new JsonResponse([
                'success' => false,
                'error' => 'Invalid input format'
            ], Response::HTTP_BAD_REQUEST);
        }

        $selectedGenres = $data['genres'];
        $selectedAnimes = $data['animes'];

        // Retourner les genres et les animes sélectionnés
        return new JsonResponse([
            'success' => true,
            'selectedGenres' => $selectedGenres,
            'selectedAnimes' => $selectedAnimes,
        ]);
    }
}
