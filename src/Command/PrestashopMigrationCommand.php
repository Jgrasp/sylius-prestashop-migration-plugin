<?php

namespace Jgrasp\PrestashopMigrationPlugin\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class PrestashopMigrationCommand extends Command
{
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;

    public function __construct(ContainerInterface $container, EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $drop = $this->getApplication()->find('doctrine:database:drop');
        $drop->run(new ArrayInput(['--force' => true]), $output);

        $create = $this->getApplication()->find('doctrine:database:create');
        $create->run(new ArrayInput([]), $output);

        $schema = $this->getApplication()->find('doctrine:schema:create');
        $schema->run(new ArrayInput([]), $output);

        $repository = $this->container->get('prestashop.repository.lang');

        $langs = $repository->findAll();

        foreach ($langs as $language) {
            $locale = $this->container->get('prestashop.data_transformer.locale')->transform($language);
            $this->entityManager->persist($locale);
        }

        $this->entityManager->flush();

        $employees = $this->container->get('prestashop.repository.employee')->findAll();

        foreach ($employees as $employee) {
            $adminUser = $this->container->get('prestashop.data_transformer.admin_user')->transform($employee);
            $this->entityManager->persist($adminUser);
        }

        $this->entityManager->flush();

        return Command::SUCCESS;
    }
}
