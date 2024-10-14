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


    //public function findUniqueGenres(): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->select('DISTINCT a.genre')
    //            ->getQuery()
    //            ->getResult();
    //    }


    //    /**
    //     * @return Anime[] Returns an array of Anime objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('a.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Anime
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
