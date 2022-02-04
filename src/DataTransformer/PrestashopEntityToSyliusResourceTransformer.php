<?php

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer;

use InvalidArgumentException;
use Sylius\Component\Resource\Model\ResourceInterface;

final class PrestashopEntityToSyliusResourceTransformer implements DataTransformerInterface
{
    private DataTransformerInterface $dataTransformer;

    public function __construct(DataTransformerInterface $dataTransformer)
    {
        $this->dataTransformer = $dataTransformer;
    }

    public function transform($data): ResourceInterface
    {
        $resource = $this->dataTransformer->transform($data);

        if (!$resource instanceof ResourceInterface) {
            throw new InvalidArgumentException(sprintf('$resource should be an instance of %s.', ResourceInterface::class));
        }

        return $resource;
    }
}
