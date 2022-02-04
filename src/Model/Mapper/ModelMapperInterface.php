<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Mapper;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

interface ModelMapperInterface
{
    public function map(array $data): ModelInterface;
}
