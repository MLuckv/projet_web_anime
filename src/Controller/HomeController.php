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

        $anime = $this->animeRepository->createQueryBuilder('a')
            ->select('a.id, a.Nom')
            ->where('LOWER(a.Nom) LIKE :Nom')
            ->setParameter('Nom',"%$q%")
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
        return new JsonResponse($anime);
    }

    #[Route('/detail', name: 'detail')]
    public function detail(): Response
    {
        return $this->render('home/detail.html.twig');
    }
}
