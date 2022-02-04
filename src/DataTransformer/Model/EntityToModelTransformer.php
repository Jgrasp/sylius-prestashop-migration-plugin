<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model;

use InvalidArgumentException;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\DataTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\Mapper\ModelMapperInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

final class EntityToModelTransformer implements DataTransformerInterface
{
    private ModelMapperInterface $mapper;

    public function __construct(ModelMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function transform($data): ModelInterface
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('$data should be an array.');
        }

        return $this->mapper->map($data);
    }
}
