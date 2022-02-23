<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model;

use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;

interface ModelTransformerInterface
{
    public function transform(array $data): ModelInterface;
}
