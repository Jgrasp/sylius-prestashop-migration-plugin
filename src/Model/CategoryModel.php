<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;

class CategoryModel implements ModelInterface
{
    #[Field(source: 'id_category', target: 'id')]
    public int $id;

    #[Field(source: 'id_parent')]
    public int $parent;

    #[Field(source: 'name', target: 'name')]
    public string $name;

    #[Field(source: 'link_rewrite', target: 'slug')]
    public string $slug;
}
