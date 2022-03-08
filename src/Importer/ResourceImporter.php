<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Importer;

use Doctrine\ORM\EntityManagerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataCollector\DataCollectorInterface;

use Jgrasp\PrestashopMigrationPlugin\Persister\PersisterInterface;
use Jgrasp\PrestashopMigrationPlugin\Validator\ViolationBagInterface;

class ResourceImporter implements ImporterInterface
{
    private string $name;

    private int $step;

    private DataCollectorInterface $collector;

    private PersisterInterface $persister;

    private EntityManagerInterface $entityManager;

    private ViolationBagInterface $violationBag;

    public function __construct(
        string                 $name,
        int                    $step,
        DataCollectorInterface $collector,
        PersisterInterface     $persister,
        EntityManagerInterface $entityManager,
        ViolationBagInterface $violationBag
    )
    {
        $this->name = $name;
        $this->step = $step;
        $this->collector = $collector;
        $this->persister = $persister;
        $this->entityManager = $entityManager;
        $this->violationBag = $violationBag;
    }

    public function import(callable $callable = null): void
    {
        $offset = 0;

        while ($offset < $this->size()) {

            $collection = $this->collector->collect($this->step, $offset);

            foreach ($collection as $item) {
                $this->persister->persist($item);
            }

            $this->entityManager->flush();

            $offset += $this->step;

            if (null !== $callable) {
                $callable($this->step, $this->violationBag->all());
            }
        }
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
