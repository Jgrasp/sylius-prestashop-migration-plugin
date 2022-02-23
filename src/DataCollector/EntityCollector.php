<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class EntityCollector implements DataCollectorInterface
{
    private EntityRepositoryInterface $repository;

    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function collect(int $limit, int $offset): array
    {
        return $this->repository->findAll($limit, $offset);
    }

    public function size(): int
    {
        return $this->repository->count();
    }

}
