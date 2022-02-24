<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Persister\Taxon;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\Persister\PersisterInterface;

class TaxonPersister implements PersisterInterface
{
    private PersisterInterface $persister;

    private EntityManagerInterface $manager;

    public function __construct(PersisterInterface $persister, EntityManagerInterface $manager)
    {
        $this->persister = $persister;
        $this->manager = $manager;
    }

    public function persist(array $data): void
    {
        $this->persister->persist($data);
        $this->manager->flush();
    }
}
