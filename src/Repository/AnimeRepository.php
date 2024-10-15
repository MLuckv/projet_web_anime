<?php

namespace App\Repository;

use App\Entity\Anime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Anime>
 */
class AnimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Anime::class);
    }


    public function findGenresWithCountsAndWatching(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.Genre, a.Watching')
            ->getQuery()
            ->getResult();

        $genreData = [];

        foreach ($results as $result) {
            $animeGenres = explode(',', $result['Genre']);
            $watchingCount = $result['Watching'] ?? 0;  // Si null, initialiser à 0

            foreach ($animeGenres as $genre) {
                $genre = trim($genre);

                // Initialiser l'entrée si elle n'existe pas
                if (!array_key_exists($genre, $genreData)) {
                    $genreData[$genre] = [
                        'count' => 0,
                        'watching' => 0
                    ];
                }

                $genreData[$genre]['count']++;

                $genreData[$genre]['watching'] += $watchingCount;
            }
        }

        return $genreData;
    }





    //    public function findGenresWithCounts(): array
    //    {
    //        $results = $this->createQueryBuilder('a')
    //            ->select('a.Genre')
    //            ->getQuery()
    //            ->getResult();
    //
    //        $genreCounts = [];
    //
    //        foreach ($results as $result) {
    //            $animeGenres = explode(',', $result['Genre']);
    //
    //            foreach ($animeGenres as $genre) {
    //                $genre = trim($genre);
    //
    //                if (!array_key_exists($genre, $genreCounts)) {
    //                    $genreCounts[$genre] = 0;
    //                }
    //
    //                $genreCounts[$genre]++;
    //            }
    //        }
    //
    //        return $genreCounts;
    //    }
    //
    //    public function findUniqueGenres(): array
    //    {
    //        $results = $this->createQueryBuilder('a')
    //            ->select('a.Genre') // Utilisez le nom correct du champ
    //            ->getQuery()
    //            ->getResult();
    //
    //        // Liste pour stocker les genres uniques
    //        $genres = [];
    //
    //        foreach ($results as $result) {
    //            // Séparer les genres par virgule
    //            $animeGenres = explode(',', $result['Genre']);
    //
    //            foreach ($animeGenres as $genre) {
    //                $genre = trim($genre); // Supprime les espaces autour
    //                if (!in_array($genre, $genres)) {
    //                    $genres[] = $genre;
    //                }
    //            }
    //        }
    //
    //        return $genres;
    //    }
    //
    //public function findUniqueGenres(): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->select('DISTINCT a.genre')
    //            ->getQuery()
    //            ->getResult();
    //    }

}
