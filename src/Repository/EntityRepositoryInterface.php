<?php

namespace Jgrasp\PrestashopMigrationPlugin\Repository;

interface EntityRepositoryInterface
{
    public function find(int $id): array;

    public function findAll(int $limit = null, int $offset = null): array;

    public function findTranslations(int $id): array;

    public function count(): int;

    public function getPrimaryKey(): string;
}
