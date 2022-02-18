<?php

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

interface ResourceImporterInterface
{
    public function import(int $limit, int $offset): void;
}
