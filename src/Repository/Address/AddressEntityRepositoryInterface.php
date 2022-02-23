<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Address;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

interface AddressEntityRepositoryInterface extends EntityRepositoryInterface
{
    public function findCustomerAddresses(int $limit = null, int $offset = null): array;

    public function countCustomerAddresses(): int;
}
