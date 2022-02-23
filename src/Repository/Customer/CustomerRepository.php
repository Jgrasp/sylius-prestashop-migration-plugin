<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Customer;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class CustomerRepository extends EntityRepository
{
    public function findAllNotGuest(int $limit = null, int $offset = null): array
    {
        $query = $this->createQueryBuilder();

        $query->select('*')
            ->from($this->getTable())
            ->where($query->expr()->neq('is_guest', true));

        if (null !== $limit && null !== $offset) {
            $query
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        }

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    public function countAllNotGuest(): int
    {
        $query = $this->createQueryBuilder();
        $query
            ->select(sprintf('COUNT(%s)', $this->getPrimaryKey()))
            ->from($this->getTable())
            ->where($query->expr()->neq('is_guest', true));

        return (int)$this->getConnection()->executeQuery($query)->fetchOne();
    }
}
