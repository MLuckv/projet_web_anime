<?php

namespace App\Repository;

use App\Entity\Anime;
use App\Entity\User;
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

    // Repository : AnimeRepository.php

    public function findGenreWithAnime($limit): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.Genre, a.Nom, a.ImageUrl')
            ->orderBy('a.Popularity', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();

        $genreToAnimes = [];

        foreach ($results as $result) {
            $animeGenres = explode(',', $result['Genre']);

            foreach ($animeGenres as $genre) {
                $genre = trim($genre);


                if (!array_key_exists($genre, $genreToAnimes)) {
                    $genreToAnimes[$genre] = [];
                }

                $animeData = [
                    'Nom' => $result['Nom'],
                    'ImageUrl' => $result['ImageUrl']
                ];

                $genreToAnimes[$genre][] = $animeData;
            }
        }

        return $genreToAnimes;
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


    public function findAnimesByName(string $name, int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.Nom')
            ->where('LOWER(a.Nom) LIKE LOWER(:Nom)')
            ->setParameter('Nom', "%$name%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAnimesByGenre(string $genre, int $limit): array
    {
        return $this->createQueryBuilder('a')
            ->select('a.id, a.Genre')
            ->where('LOWER(a.Genre) LIKE LOWER(:Genre)')
            ->setParameter('Genre', "%$genre%")
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }


    public function countAnimesByGenreForUser(User $user): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.Genre AS genre, COUNT(r.id) AS count')
            ->join('a.rates', 'r') // nombre de rate = nombre de genre vu (rate=0 = juste vue)
            ->where('r.user = :user')
            ->setParameter('user', $user)
            ->groupBy('a.Genre')
            ->orderBy('count', 'DESC')
            ->getQuery()
            ->getResult();


        $genreCounts = [];


        foreach ($results as $result) {
            $animeGenres = explode(',', $result['genre']);
            $count = (int) $result['count'];

            foreach ($animeGenres as $genre) {
                $genre = trim($genre);

                if (!array_key_exists($genre, $genreCounts)) {
                    $genreCounts[$genre] = 0;
                }

                $genreCounts[$genre] += $count;
            }
        }

        // Retourner les genres et leur nombre pour le graphique
        $formattedResults = [];
        foreach ($genreCounts as $genre => $count) {
            $formattedResults[] = ['genre' => $genre, 'count' => $count];
        }

        return $formattedResults;

    }


    public function averageRatingByGenreForUser(User $user): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.Genre AS genre, AVG(r.rating) AS averageRating')
            ->join('a.rates', 'r')
            ->where('r.user = :user')
            ->andWhere('r.rating > 0') // Exclure les notes égales à zéro
            ->setParameter('user', $user)
            ->groupBy('a.Genre')
            ->orderBy('averageRating', 'DESC')
            ->getQuery()
            ->getResult();


        $genreAverages = [];


        foreach ($results as $result) {
            $animeGenres = explode(',', $result['genre']);
            $averageRating = (float) $result['averageRating']; // Moyenne des notes pour ce groupe de genres

            foreach ($animeGenres as $genre) {
                $genre = trim($genre);

                // Si le genre n'existe pas dans le tableau ajouter avec moyenne
                if (!array_key_exists($genre, $genreAverages)) {
                    $genreAverages[$genre] = [
                        'totalRating' => 0,
                        'count' => 0,
                    ];
                }

                $genreAverages[$genre]['totalRating'] += $averageRating;
                $genreAverages[$genre]['count']++;
            }
        }

        $finalAverages = [];
        foreach ($genreAverages as $genre => $data) {
            $finalAverages[] = [
                'genre' => $genre,
                'averageRating' => $data['totalRating'] / $data['count'] // Moyenne pour chaque genre
            ];
        }

        return $finalAverages;
    }


    public function findUniqueGenres(): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.Genre') // Utilisez le nom correct du champ
            ->getQuery()
            ->getResult();

        // Liste pour stocker les genres uniques
        $genres = [];

        foreach ($results as $result) {
            // Séparer les genres par virgule
            $animeGenres = explode(',', $result['Genre']);

            foreach ($animeGenres as $genre) {
                $genre = trim($genre); // Supprime les espaces autour
                if (!in_array($genre, $genres)) {
                    $genres[] = $genre;
                }
            }
        }

        return $genres;
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
    //
    //public function findUniqueGenres(): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->select('DISTINCT a.genre')
    //            ->getQuery()
    //            ->getResult();
    //    }

}
