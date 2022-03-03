<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Attribute;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class AttributeGroupModel implements ModelInterface
{
    #[Field(source: 'id_attribute_group', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'position', target: 'position')]
    public int $position;

    #[Field(source: 'name', target: 'value', translatable: true)]
    public array $name;
}
