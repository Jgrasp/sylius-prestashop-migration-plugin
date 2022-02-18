<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Category;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class CategoryModel implements ModelInterface
{
    #[Field(source: 'id_category', target: 'prestashopId')]
    public int $id;

    #[Field(source: 'id_parent')]
    public int $parent;

    #[Field(source: 'name', target: 'name')]
    public string $name;

    #[Field(source: 'description', target: 'description')]
    public ?string $description;

    #[Field(source: 'link_rewrite', target: 'slug')]
    public string $slug;

    public function getId(): int
    {
        return $this->id;
    }

}
