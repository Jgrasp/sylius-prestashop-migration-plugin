<?php

namespace Jgrasp\PrestashopMigrationPlugin\Factory;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;

final class TaxonFactory implements FactoryInterface
{
    private Factory $factory;

    public function __construct(Factory $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param ModelInterface $model
     *
     * @return ResourceInterface
     */
    public function createNew(ModelInterface $model): TaxonInterface
    {
        $taxon = $this->factory->createNew($model);

        return $taxon;
    }

}
