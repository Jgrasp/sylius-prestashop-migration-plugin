<?php

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\TransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class ResourceImporter implements ResourceImporterInterface
{
    private string $name;

    private EntityRepositoryInterface $repository;

    private TransformerInterface $transformer;

    private EntityManagerInterface $entityManager;

    public function __construct(
        string                    $name,
        EntityRepositoryInterface $repository,
        TransformerInterface      $transformer,
        EntityManagerInterface    $entityManager
    )
    {
        $this->name = $name;
        $this->repository = $repository;
        $this->transformer = $transformer;
        $this->entityManager = $entityManager;
    }

    public function import(int $limit, int $offset): void
    {
        $data = $this->repository->findAll($limit, $offset);

        foreach ($data as $row) {
            $entity = $this->transformer->transform($row);
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function size(): int
    {
        return $this->repository->count();
    }

    public function getName(): string
    {
        return $this->name;
    }
}
