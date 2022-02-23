<?php

namespace Jgrasp\PrestashopMigrationPlugin\Provider;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Product\Factory\ProductFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

class ProductResourceProvider implements ResourceProviderInterface
{
    private ResourceProviderInterface $provider;

    /**
     * @var ProductFactoryInterface $factory
     */
    private FactoryInterface $factory;

    public function __construct(ResourceProviderInterface $provider, FactoryInterface $factory)
    {
        $this->provider = $provider;
        $this->factory = $factory;
    }

    public function getResource(ModelInterface $model): ResourceInterface
    {
        $resource = $this->provider->getResource($model);

        if ($resource->getPrestashopId() === null) {
            $resource = $this->factory->createWithVariant();
        }

        return $resource;
    }
}
