<?php

namespace App\Controller;

use App\Repository\AnimeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stat', name: 'stat_')]
class StatController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(AnimeRepository $animeRepository): Response
    {
        //chercher les genre
        $genres = $animeRepository->findUniqueGenres();

        return $this->render('stat/index.html.twig', [
            'genres' => $genres,
        ]);
    }
}
