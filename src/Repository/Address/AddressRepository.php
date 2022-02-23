<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Address;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class AddressRepository extends EntityRepository implements AddressEntityRepositoryInterface
{
    public function findCustomerAddresses(int $limit = null, int $offset = null): array
    {
        $query = $this->createQueryBuilder();

        $query->select('*')
            ->from($this->getTable())
            ->where($query->expr()->neq('id_customer', 0));

        if (null !== $limit && null !== $offset) {
            $query
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        }

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    public function countCustomerAddresses(): int
    {
        $query = $this->createQueryBuilder();
        $query
            ->select(sprintf('COUNT(%s)', $this->getPrimaryKey()))
            ->from($this->getTable())
            ->where($query->expr()->neq('id_customer', 0));

        return (int)$this->getConnection()->executeQuery($query)->fetchOne();
    }
}
