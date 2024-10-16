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
        //chercher les informations nécessaire
        $genresData = $animeRepository->findGenresWithCountsAndWatching();
        $dataPoints = [];

        //dd($genresData);

        foreach ($genresData as $genre => $data) {
            $dataPoints[] = [
                'x' => $data['watching'],
                'y' => $data['count'],
                'label' => $genre
            ];
        }

        //dd($dataPoints);

        return $this->render('stat/index.html.twig', [
            'data' => json_encode($dataPoints),// Passer les tableau pour les graph
        ]);
    }
}
