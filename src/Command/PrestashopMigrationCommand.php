<?php

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Repository\CategoryRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PrestashopMigrationCommand extends Command
{


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        return Command::FAILURE;
    }
}
