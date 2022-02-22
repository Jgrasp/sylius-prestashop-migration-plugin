<?php

namespace Jgrasp\PrestashopMigrationPlugin\Model\Category;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class CategoryModel implements ModelInterface
{
    #[Field(source: 'id_category', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_parent')]
    public int $parent;

    #[Field(source: 'active', target: 'enabled')]
    public bool $enabled;

    #[Field(source: 'position', target: 'position')]
    public int $position;

    #[Field(source: 'name', target: 'name', translatable: true)]
    public array $name;

    #[Field(source: 'description', translatable: true)]
    public array $description;

    #[Field(source: 'link_rewrite', translatable: true)]
    public array $slug;


}
