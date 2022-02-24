<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Importer\ImporterInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ResourceCommand extends Command
{
    private string $name;

    private ImporterInterface $importer;

    public function __construct(string $name, ImporterInterface $importer)
    {
        parent::__construct();

        $this->name = ucfirst(StringInflector::nameToCamelCase($name));
        $this->importer = $importer;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $io->title(sprintf('Start migration of "%s"', $this->name));

        $progressBar = new ProgressBar($output, $this->importer->size());
        $progressBar->setFormat('%percent:3s%% [%bar%] %elapsed:6s%/%estimated:-6s%');

        $this->importer->import(function (int $step) use ($progressBar) {
            $progressBar->advance($step);
        });

        $progressBar->finish();

        $io->newLine(2);
        $io->success('Migration successfull');
        $io->writeln('---------------------------------------------------------------------------');

        return Command::SUCCESS;
    }
}
