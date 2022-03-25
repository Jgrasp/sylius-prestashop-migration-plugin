<?php
declare(strict_types=1);

namespace Jgrasp\PrestashopMigrationPlugin\Resolver;

use Jgrasp\PrestashopMigrationPlugin\Repository\Configuration\ConfigurationRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;

class ConfigurationResolver
{
    /**
     * @var ConfigurationRepository
     */
    private EntityRepositoryInterface $configurationRepository;

    public function __construct(EntityRepositoryInterface $configurationRepository)
    {
        $this->configurationRepository = $configurationRepository;
    }

    public function hasStockEnabled(): bool
    {
        return $this->configurationRepository->getStockEnabled();
    }
}
