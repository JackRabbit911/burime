<?php

declare(strict_types=1);

namespace Auth\Command;

use Auth\Api\Job\TokensGC;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Sys\Console\CallApi;

#[AsCommand(name: 'clear:tokens')]
class ClearTokens extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Clear expired tokens')
            ->setHelp('This command clear expired refresh tokens...')
            ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $count = (new CallApi(TokensGC::class))->execute();
        $output->writeln("$count tokens was deleted");

        return Command::SUCCESS;
    }
}
