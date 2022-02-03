<?php

namespace Jgrasp\PrestashopMigrationPlugin\Factory;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class ProductFactory implements FactoryInterface
{
    private FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createNew(ModelInterface $model): ResourceInterface
    {
        return $this->factory->createNew($model);
    }
}
