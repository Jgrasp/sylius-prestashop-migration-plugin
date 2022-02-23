<?php

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Importer\ResourceImporterCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class PrestashopMigrationCommand extends Command
{
    private ResourceImporterCollection $resourceImporterCollection;

    public function __construct(ResourceImporterCollection $resourceImporterCollection)
    {
        parent::__construct();

        $this->resourceImporterCollection = $resourceImporterCollection;
    }

    public function configure()
    {
        $this
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Use force will erase the entire database.')
            ->addOption('resource', 'r', InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, 'You can put multiple values separated by a comma.', []);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force = $input->getOption('force');

        if ($force) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('The database will be erase before the migration. Are you sure you want continue ? (N/y) ', false);

            if (!$helper->ask($input, $output, $question)) {
                $output->write('<error>Import abort !</error>');

                return Command::FAILURE;
            }

            $drop = $this->getApplication()->find('doctrine:database:drop');
            $drop->run(new ArrayInput(['--force' => true]), $output);

            $create = $this->getApplication()->find('doctrine:database:create');
            $create->run(new ArrayInput([]), $output);

            $schema = $this->getApplication()->find('doctrine:schema:create');
            $schema->run(new ArrayInput([]), $output);
        }

        $resources = $input->getOption('resource');

        $progressBar = new ProgressBar($output, $this->resourceImporterCollection->size($resources));
        $progressBar->setFormat('%percent:3s%% [%bar%] %elapsed:6s%/%estimated:-6s%');

        $this->resourceImporterCollection->run($resources, function (int $current) use ($output, $progressBar) {
            $progressBar->setProgress($current);
        });

        $progressBar->finish();

        return Command::SUCCESS;
    }
}
