<?php


namespace App\DataFixtures;

use App\Entity\Administrateur;
use App\Entity\Client;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Creates sample Administrateur and Client users for testing.
 *
 * To load these fixtures, run:
 * php bin/console doctrine:fixtures:load
 */
class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    // Inject the password hasher service into the fixture
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // --- 1. Create the Administrateur ---
        $admin = new Administrateur();
        $admin->setNom('Dupont');
        $admin->setPrenom('Marc');
        $admin->setEmail('admin@test.com');
        $admin->setDateEmbauche(new DateTime('2023-01-15'));
        // The role field is set directly on the entity, even though the getRoles() method determines the final roles.
        // Based on your entity definition, we'll set it here, though the actual role check happens in getRoles().
        $admin->setRole('Admin');

        // Hash the password 'testpass'
        $hashedPasswordAdmin = $this->passwordHasher->hashPassword(
            $admin,
            'testpass' // The plain password for testing
        );
        $admin->setPassword($hashedPasswordAdmin);

        $manager->persist($admin);

        // --- 2. Create the Client ---
        $client = new Client();
        $client->setNom('Lefevre');
        $client->setPrenom('Sophie');
        $client->setEmail('client@test.com');
        $client->setTelephone('0612345678');
        $client->setAdresse('123 Rue de la Test, 75001 Paris');
        $client->setDateInscription(new DateTime('now'));

        // Assign the new client to the admin (satisfies the ManyToOne relationship)
        $client->setAdministrateur($admin);

        // Hash the password 'testpass'
        $hashedPasswordClient = $this->passwordHasher->hashPassword(
            $client,
            'testpass' // The plain password for testing
        );
        $client->setPassword($hashedPasswordClient);

        $manager->persist($client);

        // Save everything to the database
        $manager->flush();
    }
}