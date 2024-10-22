<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use DateTime;

/**
 * @extends ServiceEntityRepository<User>
 */
class UsersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUserAge(User $user): ?int
    {
        if ($user->getBirthday()) {
            $birthday = DateTime::createFromFormat('Y-m-d', $user->getBirthday());
            if ($birthday) {
                $currentDate = new DateTime();
                $age = $currentDate->diff($birthday)->y;
                return $age;
            }
        }
        return null; // Retourne null si date de naissance pas valide
    }

    public function findByUsername(string $username): ?User
    {
        return $this->createQueryBuilder('u')
            ->where('LOWER(u.Username) LIKE LOWER(:Username)')
            ->setParameter('Username', "%$username%")
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }


}
