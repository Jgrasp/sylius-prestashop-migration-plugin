<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Currency;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class CurrencyRepository extends EntityRepository
{
    public function getCurrencyIdByShopId(int $shopId)
    {
        $query = $this
            ->createQueryBuilder()
            ->select('*')
            ->from($this->getTableShop())
            ->where('id_shop='.$shopId);

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    protected function getTableShop(): string
    {
        return sprintf('%s_%s', $this->getTable(), 'shop');
    }

    private function getTableShopAlias(): string
    {
        return $this->getTableShop();
    }

    private function getShopCondition(int $shopId): string
    {
        return sprintf('%s.%s=%d', $this->getTableShopAlias(), 'id_shop', $shopId);
    }

    private function getTableCondition(): string
    {
        return sprintf('%s.%s=%s.%s', $this->getTable(), $this->getPrimaryKey(), $this->getTableShopAlias(), $this->getPrimaryKey());
    }
}
