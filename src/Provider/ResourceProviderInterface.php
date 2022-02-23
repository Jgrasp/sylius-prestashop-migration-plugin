<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Provider;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

interface ResourceProviderInterface
{
    public function getResource(ModelInterface $model): ResourceInterface;
}
