<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Metadata\Storage\MetadataStorage;
use Doctrine\Migrations\Version\Direction;
use Doctrine\Migrations\Version\ExecutionResult;
use Symfony\Component\Console\Command\Command;
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

    private DependencyFactory $dependencyFactory;

    public function __construct(iterable $commands, DependencyFactory $dependencyFactory)
    {
        $this->commands = $commands instanceof \Traversable ? iterator_to_array($commands) : $commands;

        parent::__construct();

        $this->dependencyFactory = $dependencyFactory;
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

            $create = $this->getApplication()->find('doctrine:schema:update');
            $create->run(new ArrayInput(['--force' => true]), $output);

            $metadataStorage = $this->dependencyFactory->getMetadataStorage();
            $metadataStorage->ensureInitialized();

            foreach ($this->dependencyFactory->getMigrationPlanCalculator()->getMigrations()->getItems() as $migration) {
                $metadataStorage->complete(new ExecutionResult($migration->getVersion(), Direction::UP, new \DateTimeImmutable()));
            }
        }

        foreach ($this->commands as $command) {
            $command->run(new ArrayInput([]), $output);
        }

        $postConfiguration = $this->getApplication()->find('prestashop:post_configuration');
        $postConfiguration->run(new ArrayInput(['--no-interaction' => true]), $output);

        return Command::SUCCESS;
    }
}
