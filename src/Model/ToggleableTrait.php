<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Model;

use Jgrasp\PrestashopMigrationPlugin\Attribute\Field;

trait ToggleableTrait
{
    #[Field(source: 'active', target: 'enabled')]
    public bool $enabled;
}
