<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

interface ResourceImporterInterface
{
    public function import(int $limit, int $offset): void;

    public function size(): int;

    public function getName(): string;
}
