<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'init:users',
    description: 'Add a short description for your command',
)]
class InitUsersCommand extends Command
{
    private const USERS = [
        ['manager1@mail.com', 'password', 'ROLE_MANAGER'],
        ['client1@mail.com', 'password', 'ROLE_CLIENT'],
    ];

    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManager;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ) {
        $this->passwordHasher = $passwordHasher;
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach (self::USERS as $userData) {
            $user = new User();
            $user->setEmail($userData[0]);
            $user->setPassword($this->passwordHasher->hashPassword($user, $userData[1]));
            $user->setRoles([$userData[2]]);

            $this->entityManager->persist($user);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
