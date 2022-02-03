<?php

namespace Jgrasp\PrestashopMigrationPlugin\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Field
{
    public string $source;

    public ?string $target;

    public function __construct(string $source, ?string $target = null)
    {
        $this->source = $source;
        $this->target = $target;
    }
}
