<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector;

interface DataCollectorInterface
{
    public function collect(int $limit, int $offset): array;

    public function size(): int;

}
