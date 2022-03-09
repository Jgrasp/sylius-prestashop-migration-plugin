<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Configurator;

interface ConfiguratorInterface
{
    public function execute(): void;

    public function getName(): string;

    public function getDefaultPriority(): int;
}
