<?php

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataCollector\DataCollectorInterface;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\TransformerInterface;

class ResourceImporter implements ResourceImporterInterface
{
    private string $name;

    private DataCollectorInterface $collector;

    private TransformerInterface $transformer;

    private EntityManagerInterface $entityManager;

    public function __construct(
        string                    $name,
        DataCollectorInterface $collector,
        TransformerInterface      $transformer,
        EntityManagerInterface    $entityManager
    )
    {
        $this->name = $name;
        $this->collector = $collector;
        $this->transformer = $transformer;
        $this->entityManager = $entityManager;
    }

    public function import(int $limit, int $offset): void
    {
        $data = $this->collector->collect($limit, $offset);

        foreach ($data as $row) {
            $entity = $this->transformer->transform($row);
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function size(): int
    {
        return $this->collector->size();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
