<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Category;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function findAll(int $limit = null, int $offset = null): array
    {
        $query = $this
            ->createQueryBuilder()
            ->select('*')
            ->from($this->getTable())
            ->orderBy('id_parent', 'ASC');

        if (null !== $limit && null !== $offset) {
            $query
                ->setMaxResults($limit)
                ->setFirstResult($offset);
        }

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }
}
