<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ResourceTransformerInterface
{
    public function transform(ModelInterface $model): ?ResourceInterface;
}
