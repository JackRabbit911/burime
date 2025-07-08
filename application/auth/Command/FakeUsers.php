<?php

declare(strict_types=1);

namespace Auth\Command;

use Sys\Console\CallApi;
use Auth\Dev\FakeUsersGenerator;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'fake:users')]
class FakeUsers extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Creates fake users.')
            ->setHelp('This command allows you to create fake users...')
            ->addArgument('count', InputArgument::REQUIRED, 'count of users')
            ->addArgument('locale', InputArgument::OPTIONAL, 'locale (en-US)')
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $input->getArgument('count');
        $locale = str_replace('-', '_', $input->getArgument('locale') ?? 'en_US');

        $args = ['locale' => $locale];
        $data = ['count' => $count];

        $output->writeln('wait please...');

        (new CallApi(FakeUsersGenerator::class, 'generate', $args))->execute($data);

        $io->success("$count users was created successfully");

        return Command::SUCCESS;
    }
}
