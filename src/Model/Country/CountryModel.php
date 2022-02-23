<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Country;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ToggleableTrait;

class CountryModel implements ModelInterface
{
    use ToggleableTrait;

    #[Field(source: 'id_country', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'iso_code', target: 'code')]
    public string $code;
}
