<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model\Currency;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

class CurrencyModel implements ModelInterface
{
    #[Field(source: 'id_currency', target: 'prestashopId', id: true)]
    public int $id;

    #[Field(source: 'iso_code', target: 'code')]
    public string $code;

}
