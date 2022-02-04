<?php

namespace Jgrasp\PrestashopMigrationPlugin\Provider;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResourceProvider implements ResourceProviderInterface
{
    private RepositoryInterface $repository;

    private FactoryInterface $factory;

    public function __construct(RepositoryInterface $repository, FactoryInterface $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    public function getResource(ModelInterface $model): ResourceInterface
    {
        $resource = $this->repository->findOneBy(['prestashopId' => $model->getId()]);

        if (!$resource) {
            $resource = $this->factory->createNew();
        }

        return $resource;
    }
}
