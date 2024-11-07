<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AlgorithmController extends AbstractController
{
    private AnimeRepository $AnimeRepository;
    public function __construct(AnimeRepository $AnimeRepository)
    {
        $this->AnimeRepository = $AnimeRepository;
    }


    #[Route('/algorithm', name: 'app_algorithm')]
    public function index(): Response
    {
        $genres = $this->AnimeRepository->findUniqueGenres();

        return $this->render('algorithm/index.html.twig',[
            'genres' => $genres,

            ]);
    }
}
