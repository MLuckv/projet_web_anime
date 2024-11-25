<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
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
        $genres = $this->AnimeRepository->findGenreWithAnime(100);

        return $this->render('algorithm/index.html.twig', [
            'genres' => $genres,
        ]);
    }

    #[Route('/recommend', name: 'recommend', methods: ['POST'])]
    public function recommend(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['genres']) || !isset($data['animes']) || !isset($data['populationSize']) || !isset($data['recommendationCount'])) {
            return new JsonResponse(['success' => false, 'error' => 'Invalid input format'], Response::HTTP_BAD_REQUEST);
        }

        $genres = $data['genres'];
        $animes = $data['animes'];
        $populationSize = (float) $data['populationSize'];
        $recommendationCount = (int) $data['recommendationCount'];

        $pythonPath = 'C:\Users\vmoul\anaconda3\python.exe'; // Remplacez par le chemin vers Python
        $scriptPath = 'C:\wamp64\www\mv\projet_web_anime\algoRecomandation\algoRecoWithGenreAndAnime.py';

        $process = new Process([
            $pythonPath,
            $scriptPath,
            $recommendationCount,
            $populationSize,
            json_encode($genres),
            json_encode($animes),
        ]);

        $process->setTimeout(3600);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $output = json_decode($process->getOutput(), true);


        return new JsonResponse([
            'success' => true,
            'recommendations' => $output['recommendations'],
        ]);
    }

}
