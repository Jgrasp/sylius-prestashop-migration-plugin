<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Carrier;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class CarrierModel implements ModelInterface
{
    #[Field(source: 'id_carrier', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'name')]
    public string $name;

    #[Field(source: 'delay', target: 'description', translatable: true)]
    public array $description;

}
