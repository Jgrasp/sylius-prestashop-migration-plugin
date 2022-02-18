<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer;

use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\ModelTransformerInterface;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

final class PrestashopTransformer
{
    private ModelTransformerInterface $modelTransformer;

    private ResourceTransformerInterface $resourceTransformer;

    public function __construct(ModelTransformerInterface $modelTransformer, ResourceTransformerInterface $resourceTransformer)
    {
        $this->modelTransformer = $modelTransformer;
        $this->resourceTransformer = $resourceTransformer;
    }

    public function transform($data): ResourceInterface
    {
        $model = $this->modelTransformer->transform($data);
        $resource = $this->resourceTransformer->transform($model);

        return $resource;
    }
}
