<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer;

use Jgrasp\PrestashopMigrationPlugin\Factory\FactoryInterface;
use Jgrasp\PrestashopMigrationPlugin\Mapper\MapperInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class EntityTransformer implements DataTransformerInterface
{
    private MapperInterface $mapper;

    private FactoryInterface $factory;

    public function __construct(MapperInterface $mapper, FactoryInterface $factory)
    {
        $this->mapper = $mapper;
        $this->factory = $factory;
    }

    public function transform(array $data): ResourceInterface
    {
        $model = $this->mapper->map($data);
        $resource = $this->factory->createNew($model);

        return $resource;
    }

}
