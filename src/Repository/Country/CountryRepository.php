<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Country;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class CountryRepository extends EntityRepository
{
    public function findByZoneId(int $zoneId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getTable())
            ->where($query->expr()->eq('id_zone', $zoneId));

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }
}
