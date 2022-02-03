<?php

namespace Jgrasp\PrestashopMigrationPlugin\Factory;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface FactoryInterface
{
    public function createNew(ModelInterface $model): ResourceInterface;
}
