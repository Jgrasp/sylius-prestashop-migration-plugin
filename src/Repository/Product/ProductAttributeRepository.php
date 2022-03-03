<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Product;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class ProductAttributeRepository extends EntityRepository
{
    public function getAttributesByProductId(int $productId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select($this->getCombinationTable().'.*')
            ->from($this->getCombinationTable())
            ->join(
                $this->getCombinationTable(),
                $this->getTable(),
                $this->getTable(),
                $query->expr()->comparison($this->getTable().'.'.$this->getPrimaryKey(), '=', $this->getCombinationTable().'.'.$this->getPrimaryKey()))
            ->where($query->expr()->eq($this->getTable().'.id_product', $productId));

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    public function getAttributes(int $productAttributeId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select($this->getCombinationTable().'.*')
            ->from($this->getCombinationTable())
            ->join(
                $this->getCombinationTable(),
                $this->getTable(),
                $this->getTable(),
                $query->expr()->comparison($this->getTable().'.'.$this->getPrimaryKey(), '=', $this->getCombinationTable().'.'.$this->getPrimaryKey()))
            ->where($query->expr()->eq($this->getCombinationTable().'.id_product_attribute', $productAttributeId));

        return $this->getConnection()->executeQuery($query)->fetchAllAssociative();
    }

    private function getCombinationTable()
    {
        return $this->getTable().'_combination';
    }
}
