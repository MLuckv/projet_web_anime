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
        $genresData = $animeRepository->findGenresWithCounts();
        $genres = [];
        $counts = [];

        //dd($genresData);

        // Remplir les tableaux avec les données
        foreach ($genresData as $genre => $count) {
            $genres[] = $genre; // Nom du genre
            $counts[] = $count;  // Compte du genre
        }

        //dd($counts, $genres);

        return $this->render('stat/index.html.twig', [
            'genres' => json_encode($genres), // Passer le tableau des genres
            'counts' => json_encode($counts),   // Passer le tableau des counts
        ]);
    }
}
