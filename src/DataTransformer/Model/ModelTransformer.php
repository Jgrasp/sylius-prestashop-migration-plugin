<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelMapperInterface;

final class ModelTransformer implements ModelTransformerInterface
{
    private ModelMapperInterface $mapper;

    public function __construct(ModelMapperInterface $mapper)
    {
        $this->mapper = $mapper;
    }

    public function transform(array $data): ModelInterface
    {
        return $this->mapper->map($data);
    }
}
