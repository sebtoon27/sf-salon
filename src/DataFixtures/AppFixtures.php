<?php

namespace App\DataFixtures;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; 

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
$admin = new User();

        $admin->setEmail('admin@fake.com');
        $admin->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
        $admin->setPassword(
            $this->userPasswordHasher->hashPassword(
                $admin, '123456'
            )
        );
        $admin->setNom('Reine');
        $admin->setPrenom('Sebastien');
        $admin->setAdresse('1 rue des bordels');
        $admin->setTelephone('0669696969');
        
        $manager->persist($admin);
       
$client = new User();

        $client->setEmail('vince@fake.com');
        $client->setRoles(['ROLE_USER']);
        $client->setPassword(
            $this->userPasswordHasher->hashPassword(
                $client, '123456'
            )
        );
        $client->setNom('Lamy');
        $client->setPrenom('Vincent');
        $client->setAdresse('1 rue de la paix');
        $client->setTelephone('0669696969');

        $manager->persist($client);
        $manager->flush();
}

}

