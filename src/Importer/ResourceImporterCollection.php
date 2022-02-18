<?php

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

class ResourceImporterCollection
{
    const STEP = 100;

    /**
     * @var ResourceImporterInterface[]
     */
    private iterable $importers;

    public function __construct(iterable $importers)
    {
        $this->importers = $importers instanceof \Traversable ? iterator_to_array($importers) : $importers;
    }

    public function run(callable $callable = null): void
    {
        $cursor = 0;

        foreach ($this->importers as $importer) {
            $size = $importer->size();

            for ($i = 0; $i < $size; $i += self::STEP) {
                $cursor += self::STEP;

                if ($cursor > $size) {
                    $cursor = $size;
                }

                if (null !== $callable) {
                    $callable($cursor, $this);
                }

                $importer->import(self::STEP, $i);
            }
        }
    }

    public function size(): int
    {
        return array_sum(array_map(static fn(ResourceImporterInterface $importer) => $importer->size(), $this->importers));
    }
}
