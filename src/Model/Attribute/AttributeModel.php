<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Attribute;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class AttributeModel implements ModelInterface
{
    #[Field(source: 'id_attribute', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'id_attribute_group')]
    public int $attributeGroupId;

    #[Field(source: 'name', target: 'value', translatable: true)]
    public array $name;
}
