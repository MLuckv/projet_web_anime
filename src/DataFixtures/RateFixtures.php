<?php

namespace App\DataFixtures;

use App\Entity\Rate;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RateFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 50; $i++) {
            $rate = new Rate();

            // Récupère des références d'utilisateur et d'anime
            $user = $this->getReference('user_' . mt_rand(0, 29));
            $anime = $this->getReference('anime_' . mt_rand(0, 19));

            // Assigne l'utilisateur et l'anime à l'objet Rate
            $rate->setUser($user);
            $rate->setAnime($anime);

            // Assigne un score aléatoire
            $rate->setRating(mt_rand(1, 10));

            $manager->persist($rate);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            AppFixtures::class
        ];
    }
}
