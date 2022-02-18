<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Shop;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class ShopModel implements ModelInterface
{
    #[Field(source: 'id_shop', target: 'prestashopId')]
    public int $id;

    #[Field(source: 'name', target: 'name')]
    public string $name;

    #[Field(source: 'active', target: 'enabled')]
    public bool $enable;

    public function getId(): int
    {
        return $this->id;
    }

}
