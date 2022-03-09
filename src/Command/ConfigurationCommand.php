<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Configurator\ConfiguratorInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConfigurationCommand extends Command
{
    /** @var ConfiguratorInterface[] */
    private array $configurators;

    public function __construct(iterable $configurators)
    {
        $this->configurators = $configurators instanceof \Traversable ? iterator_to_array($configurators) : $configurators;

        parent::__construct();
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        if ($input->getOption('no-interaction') === false) {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Are you sure you want to automatically configure the store ? This can have serious repercussions on a production site. (N/y) ', false);

            if (!$helper->ask($input, $output, $question)) {
                $output->write('<error>Configuration abort !</error>');

                return Command::FAILURE;
            }
        }

        foreach ($this->configurators as $configurator) {

            $io->title(sprintf('Start configuration of "%s"', $configurator->getName()));

            $configurator->execute();

            $io->success('Configuration successfull');
            $io->writeln('---------------------------------------------------------------------------');
        }
        return Command::SUCCESS;
    }
}
