<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Zone;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class ZoneModel implements ModelInterface
{
    #[Field(source: 'id_zone', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'name', target: 'name')]
    public string $name;
}
