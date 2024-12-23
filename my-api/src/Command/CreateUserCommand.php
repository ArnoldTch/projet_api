<?php
// src/Command/CreateUserCommand.php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CreateUserCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:create-user')
            ->setDescription('Create a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Créez un nouvel utilisateur
        $user = new User();
        $user->setUsername('root');

        // Encodez le mot de passe
        $password = 'root';  // Le mot de passe que vous souhaitez attribuer
        $user->setPassword($password);

        // Assurez-vous d'avoir les bons rôles
        $user->setRoles(['ROLE_USER']);

        // Enregistrez l'utilisateur dans la base de données
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $output->writeln('User created successfully!');
        return Command::SUCCESS; // Retourner Command::SUCCESS pour indiquer que la commande a réussi
    }
}
