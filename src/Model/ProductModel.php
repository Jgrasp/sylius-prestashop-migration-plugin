<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;

class ProductModel implements ModelInterface
{
    #[Field(source: 'id_product', target: 'prestashopId')]
    public int $id;

    public function getId(): int
    {
        return $this->id;
    }

}
