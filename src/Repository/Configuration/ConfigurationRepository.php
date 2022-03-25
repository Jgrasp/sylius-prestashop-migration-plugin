<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Configuration;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class ConfigurationRepository extends EntityRepository
{
    public function getStockEnabled(): bool
    {
        $query = $this->createQueryBuilder();
        $query
            ->select('value')
            ->from($this->getTable())
            ->where($query->expr()->like('name', $query->expr()->literal('PS_STOCK_MANAGEMENT')));


        return (bool) $this->getConnection()->executeQuery($query)->fetchOne();
    }
}
