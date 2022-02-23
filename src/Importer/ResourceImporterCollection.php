<?php
declare(strict_types=1);

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

    public function run(array $filters = [], callable $callable = null): void
    {
        $cursor = 0;

        $importers = $this->getResourcesImporter($filters);

        foreach ($importers as $importer) {
            $this->runOne($importer->getName(), function ($pointer) use ($cursor, $callable) {
                $cursor += $pointer;

                if (null !== $callable) {
                    $callable($cursor);
                }
            });
        }
    }

    public function runOne(string $resource, callable $callable = null): void
    {
        $cursor = 0;

        if (!$this->hasResourceImporter($resource)) {
            throw new \Exception(sprintf("Resource importer %s does not exist", $resource));
        }

        $importer = $this->getResourceImporter($resource);
        $size = $importer->size();

        for ($i = 0; $i < $size; $i += self::STEP) {
            $cursor += self::STEP;

            if ($cursor > $size) {
                $cursor = $size;
            }

            if (null !== $callable) {
                $callable($cursor);
            }

            $importer->import(self::STEP, $i);
        }

    }

    public function size(array $filter = []): int
    {
        return array_sum(array_map(static fn(ResourceImporterInterface $importer) => $importer->size(), $this->getResourcesImporter($filter)));
    }

    private function hasResourceImporter(string $resource): bool
    {
        return null !== $this->getResourceImporter($resource);
    }

    private function getResourceImporter(string $resource): ?ResourceImporterInterface
    {
        $resources = $this->getResourcesImporter([$resource]);

        if (count($resources) > 1) {
            throw new \Exception("Resource %s exist multiple time in collection.", $resource);
        }

        $resource = reset($resources);

        return false !== $resource ? $resource : null;
    }

    private function getResourcesImporter(array $filter = []): array
    {
        if (empty($filter)) {
            return $this->importers;
        }

        $resources = array_map(static fn($resource) => str_starts_with($resource, 'prestashop.importer.') ? $resource : sprintf('prestashop.importer.%s', $resource), $filter);

        return array_filter($this->importers, static fn(ResourceImporterInterface $resourceImporter) => in_array($resourceImporter->getName(), $resources));
    }
}
