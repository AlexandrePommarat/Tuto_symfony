<?php

namespace App\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;

class LoadCategory implements FixtureInterface
{
    // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$fullname, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFullName($fullname);
            $user->setUsername($username);
            $user->setPassword($password);
            $user->setEmail($email);
            $user->setRoles($roles);

            $manager->persist($user);
            //$this->addReference($username, $user);
        }

        $manager->flush();
    }

    private function getUserData(): array
    {
        return [
            // $userData = [$fullname, $username, $password, $email, $roles];
            ['Alexandre', 'Barroblade', 'poulette', 'ap@symfony.com', ['ROLE_ADMIN']],
            ['Yohann', 'Babacoolo', 'poulette', 'yg@symfony.com', ['ROLE_MODERATEUR']],
            ['Vincent L', 'Kiwi', 'poulette', 'vl@symfony.com', ['ROLE_AUTEUR']],
            ['Vincent M', 'Babiche', 'poulette', 'vm@symfony.com', ['ROLE_USER']],
        ];
    }
}