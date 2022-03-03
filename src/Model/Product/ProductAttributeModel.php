<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Product;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class ProductAttributeModel implements ModelInterface
{
    #[Field(source: 'id_product_attribute', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_product')]
    public int $productId;

    #[Field(source: 'price')]
    public int $price;
}
