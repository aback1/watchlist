<?php


namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $users = [
            ['id' => 'abdasdsadsc', 'name' => 'John Doe', 'password' => 'peppermint'],
            ['id' => 'ddsadsadsadef', 'name' => 'Jane Doe', 'password' => 'strawberry'],
        ];

        foreach ($users as $userData) {
            $user = new User();
            $user->setId($userData['id']);
            $user->setName($userData['name']);
            $hashedPassword = $this->hasher->hashPassword($user, $userData['password']);
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }
}

