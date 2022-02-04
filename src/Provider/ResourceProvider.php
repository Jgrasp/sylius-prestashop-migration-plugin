<?php

namespace Jgrasp\PrestashopMigrationPlugin\Provider;

use Exception;
use Jgrasp\PrestashopMigrationPlugin\Entity\PrestashopTrait;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use ReflectionClass;
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
        $reflection = new ReflectionClass($this->repository->getClassName());
        $traits = $reflection->getTraitNames();

        if (in_array(PrestashopTrait::class, $traits) === false) {
            throw new Exception(sprintf("Entity %s should implement an instance of Trait %s", $this->repository->getClassName(), PrestashopTrait::class));
        }

        $resource = $this->repository->findOneBy(['prestashopId' => $model->getId()]);

        if (!$resource) {
            $resource = $this->factory->createNew();
        }

        return $resource;
    }
}
