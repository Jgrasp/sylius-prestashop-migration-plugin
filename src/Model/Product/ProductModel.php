<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Product;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class ProductModel implements ModelInterface
{
    #[Field(source: 'id_product', target: 'prestashopId')]
    public int $id;

    public function getId(): int
    {
        return $this->id;
    }

}
