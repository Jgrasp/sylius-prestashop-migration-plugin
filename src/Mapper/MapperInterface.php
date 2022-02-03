<?php

namespace Jgrasp\PrestashopMigrationPlugin\Mapper;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

interface MapperInterface
{
    public function map(array $data): ModelInterface;
}
