<?php

namespace Jgrasp\PrestashopMigrationPlugin\Repository;

interface EntityRepositoryInterface
{
    public function findAll(int $limit = 10, int $offset = 0): array;
}
