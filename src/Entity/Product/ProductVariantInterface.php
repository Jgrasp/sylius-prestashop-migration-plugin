<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Entity\Product;


interface ProductVariantInterface
{
    public function hasProduct(): bool;
}
