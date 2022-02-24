<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Importer\ResourceImporterCollection;
use Jgrasp\PrestashopMigrationPlugin\Importer\ImporterInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;

final class PrestashopMigrationCommand extends Command
{
    /**
     * @var ResourceCommand[] $commands
     */
    private array $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands instanceof \Traversable ? iterator_to_array($commands) : $commands;

        parent::__construct();
    }

    public function configure()
    {
        $this
            ->addOption('force', 'f', InputOption::VALUE_NONE, 'Use force will erase the entire database.');
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

        foreach ($this->commands as $command) {
            $command->run(new ArrayInput([]), $output);
        }

        return Command::SUCCESS;
    }
}
