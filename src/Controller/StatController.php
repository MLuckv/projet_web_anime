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
        $dataPoints1 = [];

        $animeData = $animeRepository->findAll();
        $dataPoints2 = [];

        //dd($genresData);
        //dd($animeData);

        foreach ($genresData as $genre => $data) {
            $dataPoints1[] = [
                'x' => $data['watching'],
                'y' => $data['count'],
                'label' => $genre
            ];
        }

        foreach ($animeData as $anime) {
            $dataPoints2[] = [
                'x' => $anime->getWatching(),
                'y' => $anime->getScore(),
                'r' => $anime->getEpisode(),//taille bulle
                'label' => $anime->getNom(),
                'popularity' => $anime->getPopularity(),
            ];
        }

        //dd($dataPoints1, $dataPoints2);

        return $this->render('stat/index.html.twig', [
            'data_graph1' => json_encode($dataPoints1),// Passer les tableau pour les graph
            'data_graph2' => json_encode($dataPoints2),

        ]);
    }
}
