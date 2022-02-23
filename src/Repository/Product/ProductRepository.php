<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Repository\Product;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;

class ProductRepository extends EntityRepository
{
    public function findByReference(string $reference): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getTable())
            ->where($query->expr()->like("reference", $query->expr()->literal($reference)));

        return $this->getConnection()->fetchAllAssociative($query);
    }

    public function findBySlug(string $slug): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getTableTranslation())
            ->where($query->expr()->like("link_rewrite", $query->expr()->literal($slug)));

        return $this->getConnection()->fetchAllAssociative($query);
    }

    public function getCategories(int $productId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getPrefix().'category_product')
            ->where($query->expr()->eq("id_product", $productId));

        return $this->getConnection()->fetchAllAssociative($query);
    }

    public function getShops(int $productId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getTableChannel())
            ->where($query->expr()->eq("id_product", $productId));

        return $this->getConnection()->fetchAllAssociative($query);
    }

    public function getImages(int $productId): array
    {
        $query = $this->createQueryBuilder();

        $query
            ->select('*')
            ->from($this->getPrefix().'image')
            ->where($query->expr()->eq("id_product", $productId))
            ->orderBy('position', 'ASC');

        return $this->getConnection()->fetchAllAssociative($query);
    }
}
