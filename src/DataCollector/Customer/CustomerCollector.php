<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector\Customer;

use Jgrasp\PrestashopMigrationPlugin\DataCollector\DataCollectorInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Customer\CustomerRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class CustomerCollector implements DataCollectorInterface
{
    /**
     * @var CustomerRepository $repository
     */
    private EntityRepositoryInterface $repository;

    public function __construct(EntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function collect(int $limit, int $offset): array
    {
        return $this->repository->findAllNotGuest($limit, $offset);
    }

    public function size(): int
    {
        return $this->repository->countAllNotGuest();
    }
}
