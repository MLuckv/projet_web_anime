<?php

namespace App\Controller;

use App\Entity\Anime;
use App\Form\AnimeSearch;
use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/', name: 'home_')]
class HomeController extends AbstractController
{
    private AnimeRepository $animeRepository;

    public function __construct(AnimeRepository $animeRepository)
    {
        $this->animeRepository = $animeRepository;
    }


    #[Route('', name: 'index')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(AnimeSearch::class, new Anime());
        $form->handleRequest($request);

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/search', name: 'search')]
    public function search(Request $request): JsonResponse
    {
        $q = strtolower($request->query->get('q') ?? '');

        $anime = $this->animeRepository->findAnimesByName($q, 10);
        return new JsonResponse($anime);
    }

    #[Route('/anime/{slug}', name: 'anime_detail')]
    public function animeDetail(string $slug): Response
    {
        // Extraire l'ID à partir du slug
        $animeId = explode('-', $slug)[0];

        $anime = $this->animeRepository->find($animeId);

        if (!$anime) {
            throw $this->createNotFoundException('Cet anime n\'existe pas.');
        }

        //dd($anime);

        return $this->render('home/detail.html.twig', [
            'anime' => $anime,
        ]);
    }

}
