<?php

namespace Jgrasp\PrestashopMigrationPlugin\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Field
{
    public string $source;

    public ?string $target;

    public bool $id;

    public bool $translatable;

    public function __construct(string $source, ?string $target = null, bool $id = false, bool $translatable = false)
    {
        $this->source = $source;
        $this->target = $target;
        $this->id = $id;
        $this->translatable = $translatable;
    }
}
