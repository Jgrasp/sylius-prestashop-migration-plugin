<?php

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

class ResourceImporterCollection
{
    /**
     * @var ResourceImporterInterface[]
     */
    private iterable $importers;

    public function __construct(iterable $importers)
    {
        $this->importers = $importers;
    }

    public function run(): void
    {
        foreach ($this->importers as $importer) {
            $importer->import(2000, 0);
        }
    }
}
