<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer;

use Sylius\Component\Resource\Model\ResourceInterface;

interface DataTransformerInterface
{
    public function transform(array $data): ResourceInterface;
}
