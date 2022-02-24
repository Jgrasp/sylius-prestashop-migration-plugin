<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

interface ImporterInterface
{
    public function import(callable $callable = null): void;

    public function size(): int;

    public function getName(): string;
}
