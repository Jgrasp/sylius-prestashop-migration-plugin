<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

interface ModelMapperInterface
{
    public function map(array $data): ModelInterface;
}
