<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Persister;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\TransformerInterface;

class ResourcePersister implements PersisterInterface
{
    private EntityManagerInterface $manager;

    private TransformerInterface $transformer;

    public function __construct(EntityManagerInterface $manager, TransformerInterface $transformer)
    {
        $this->manager = $manager;
        $this->transformer = $transformer;
    }

    public function persist(array $data): void
    {
        $resource = $this->transformer->transform($data);

        if (null !== $resource) {
            $this->manager->persist($resource);
        }
    }

}
