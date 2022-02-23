<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model;

interface ModelMapperInterface
{
    public function map(array $data): ModelInterface;
}
