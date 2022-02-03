<?php

namespace Jgrasp\PrestashopMigrationPlugin\Event;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Contracts\EventDispatcher\Event;

class FactoryEvent extends Event
{
    private ResourceInterface $resource;

    private ModelInterface $model;

    public function __construct(ResourceInterface $resource, ModelInterface $model)
    {

        $this->resource = $resource;
        $this->model = $model;
    }

    public function getResource(): ResourceInterface
    {
        return $this->resource;
    }

    public function getModel(): ModelInterface
    {
        return $this->model;
    }
}
