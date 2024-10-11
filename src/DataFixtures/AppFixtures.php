<?php

namespace App\DataFixtures;

use App\Entity\Anime;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        //user
        for ($i = 0; $i < 30; $i++) {
            // Crée l'utilisateur et récupère l'objet User
            $user = $this->createUser(
                $manager,
                "User $i",
                mt_rand(0, 1) ? 'Male' : 'Female',
                '199' . mt_rand(0, 9) . '-0' . mt_rand(1, 9) . '-1' . mt_rand(0, 9),
                'Location ' . mt_rand(1, 10),
                'Joined in 20' . mt_rand(10, 24),
                mt_rand(10, 500), // jours regardés
                mt_rand(1, 10), // score moyen
                mt_rand(1, 50), // animes en cours
                mt_rand(1, 100), // complétés
                mt_rand(0, 20), // en attente
                mt_rand(0, 5), // abandonnés
                mt_rand(0, 100), // planifiés
                mt_rand(1, 500), // total
                mt_rand(0, 50), // re-regardés
                mt_rand(1, 1000) // épisodes
            );

            // Ajoute une référence pour lier les rates aux users
            $this->addReference("user_".$i, $user);
        }

        // Tableau des genres d'animes
        $genres = [
            'Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy',
            'Horror', 'Mystery', 'Romance', 'Sci-Fi', 'Slice of Life',
            'Supernatural', 'Sports', 'Thriller', 'Historical', 'Magic'
        ];

        //anime
        for ($i = 0; $i < 20; $i++) {
            // Sélectionne un ou deux genres aléatoires
            $selectedGenres = array_rand(array_flip($genres), mt_rand(1, 2));
            $genreString = is_array($selectedGenres) ? implode(', ', $selectedGenres) : $selectedGenres;

            // Crée l'anime en utilisant la fonction createAnime() qui renvoie un objet Anime
            $anime = $this->createAnime(
                $manager,
                "Anime $i",
                mt_rand(5, 10) + mt_rand() / mt_getrandmax(), // score entre 5 et 10
                $genreString,
                "English Name $i",
                "Synopsis for anime $i",
                'TV',
                mt_rand(12, 100), // épisodes entre 12 et 100
                '2020-01-' . sprintf('%02d', mt_rand(1, 28)),
                'Spring ' . mt_rand(2015, 2023),
                'Studio Pierrot',
                'VIZ Media',
                'Studio Pierrot',
                'Manga',
                '24 min per ep',
                'PG-13',
                mt_rand(1, 1000), // rang
                mt_rand(1000, 100000), // popularité
                mt_rand(1000, 50000), // membres
                mt_rand(10, 1000), // favoris
                mt_rand(100, 10000), // en cours
                mt_rand(100, 5000), // complétés
                mt_rand(10, 2000), // en attente
                mt_rand(1, 500) // abandonnés
            );

            // Ajoute une référence pour lier les rates aux animes
            $this->addReference("anime_".$i, $anime);
        }

        $manager->flush();
    }



    //fonction
    private function createUser(ObjectManager $manager, string $username, string $gender, string $birthday, string $location, string $joined,
                                int $daysWatched, int $meanScore, int $watching, int $completed, int $onHold, int $dropped, int $planToWatch,
                                int $totalEntries, int $rewatched, int $episodes): User
    {
        $user = new User();
        $user->setUsername($username)
            ->setGender($gender)
            ->setBirthday($birthday)
            ->setLocation($location)
            ->setJoined($joined)
            ->setDaysWatched($daysWatched)
            ->setMeanScore($meanScore)
            ->setWatching($watching)
            ->setCompleted($completed)
            ->setOnHold($onHold)
            ->setDropped($dropped)
            ->setPlanToWatch($planToWatch)
            ->setTotalEntries($totalEntries)
            ->setRewatched($rewatched)
            ->setEpisodes($episodes);

        $manager->persist($user);

        return $user;
    }

    private function createAnime(ObjectManager $manager, string $nom, float $score, string $genre, string $englishName, string $synopsis,
                                 string $type, int $episode, string $aired, string $premiered, string $producers, string $licensors,
                                 string $studios, string $source, string $duration, string $rating, int $ranked, int $popularity, int $members,
                                 int $favorites, int $watching, int $completed, int $onHold, int $dropped): Anime
    {
        $anime = new Anime();
        $anime->setNom($nom)
            ->setScore($score)
            ->setGenre($genre)
            ->setEnglishName($englishName)
            ->setSynopsis($synopsis)
            ->setType($type)
            ->setEpisode($episode)
            ->setAired($aired)
            ->setPremiered($premiered)
            ->setProducers($producers)
            ->setLicensors($licensors)
            ->setStudios($studios)
            ->setSource($source)
            ->setDuration($duration)
            ->setRating($rating)
            ->setRanked($ranked)
            ->setPopularity($popularity)
            ->setMembers($members)
            ->setFavorites($favorites)
            ->setWatching($watching)
            ->setCompleted($completed)
            ->setOnHold($onHold)
            ->setDroped($dropped);

        $manager->persist($anime);

        return $anime;
    }

}
