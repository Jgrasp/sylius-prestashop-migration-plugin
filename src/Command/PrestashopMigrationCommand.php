<?php

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Jgrasp\PrestashopMigrationPlugin\Importer\ResourceImporterCollection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class PrestashopMigrationCommand extends Command
{
    private ResourceImporterCollection $resourceImporterCollection;


    public function __construct(ResourceImporterCollection $resourceImporterCollection)
    {
        parent::__construct();

        $this->resourceImporterCollection = $resourceImporterCollection;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drop = $this->getApplication()->find('doctrine:database:drop');
        $drop->run(new ArrayInput(['--force' => true]), $output);

        $create = $this->getApplication()->find('doctrine:database:create');
        $create->run(new ArrayInput([]), $output);

        $schema = $this->getApplication()->find('doctrine:schema:create');
        $schema->run(new ArrayInput([]), $output);

        $progressBar = new ProgressBar($output, $this->resourceImporterCollection->size());
        $progressBar->setFormat('%percent:3s%% [%bar%] %elapsed:6s%/%estimated:-6s%');

        $this->resourceImporterCollection->run(static function (int $current) use ($output, $progressBar) {
            $progressBar->setProgress($current);
        });

        $progressBar->finish();


        return Command::SUCCESS;
    }
}
