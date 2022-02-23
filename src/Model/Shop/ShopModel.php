<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Shop;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ToggleableTrait;

class ShopModel implements ModelInterface
{
    use ToggleableTrait;

    #[Field(source: 'id_shop', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'name', target: 'name')]
    public string $name;
}
