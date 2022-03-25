<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Stock;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class StockAvailableRepository extends EntityRepository
{
    public function getQuantityByProductId(int $productId): int
    {
        return $this->getQuantityByProductAttributeId($productId, 0);
    }

    public function getQuantityByProductAttributeId(int $productId, int $productAttributeId): int
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('quantity')
            ->from($this->getTable())
            ->where($query->expr()->eq('id_product', $productId))
            ->andWhere($query->expr()->eq('id_product_attribute', $productAttributeId));

        return (int)$this->getConnection()->executeQuery($query)->fetchOne();
    }
}
