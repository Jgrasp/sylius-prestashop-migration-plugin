<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataCollector\Address;

use Jgrasp\PrestashopMigrationPlugin\DataCollector\DataCollectorInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\Address\AddressEntityRepositoryInterface;

class AddressCollector implements DataCollectorInterface
{
    private AddressEntityRepositoryInterface $repository;

    public function __construct(AddressEntityRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function collect(int $limit, int $offset): array
    {
        return $this->repository->findCustomerAddresses($limit, $offset);
    }

    public function size(): int
    {
        return $this->repository->countCustomerAddresses();
    }

}
