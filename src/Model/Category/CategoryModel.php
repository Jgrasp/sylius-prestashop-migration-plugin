<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Category;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ToggleableTrait;
use Jgrasp\PrestashopMigrationPlugin\Model\UrlModelTrait;

class CategoryModel implements ModelInterface
{
    use UrlModelTrait, ToggleableTrait;

    #[Field(source: 'id_category', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_parent')]
    public int $parent;

    #[Field(source: 'position', target: 'position')]
    public int $position;

    #[Field(source: 'name', target: 'name', translatable: true)]
    public array $name;

    #[Field(source: 'description', translatable: true)]
    public array $description;

}
