<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Persister;

interface PersisterInterface
{
    public function persist(array $data): void;
}
