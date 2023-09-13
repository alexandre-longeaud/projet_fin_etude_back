<?php

namespace App\Command;

use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserAddCommand extends Command
{
    protected static $defaultName = 'app:user:add';
    protected static $defaultDescription = 'create user';
    private UserPasswordHasherInterface $passwordHasher;
    private EntityManagerInterface $entityManagerInterface;

public function __construct(UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManagerInterface)
{
    $this->passwordHasher=$passwordHasher;
    $this->entityManagerInterface= $entityManagerInterface;
    parent::__construct();
}

    protected function configure(): void
    {
    /*
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
        */
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');
        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPseudo('admin');
        $user->setPassword($this->passwordHasher->hashPassword($user,'admin'));
        $user->setRoles(['ROLE_ADMIN']);
        $user->setCreatedAt(new DateTime('now'));

        $this->entityManagerInterface->persist($user);
        $this->entityManagerInterface->flush();

        $io->success('New admin created');

        return Command::SUCCESS;
    }
}
