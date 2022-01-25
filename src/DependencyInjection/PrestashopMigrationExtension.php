<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;


final class PrestashopMigrationExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $resources = $config['resources'];
        $prefix = $config['prefix'];

        $container->setParameter('prestashop.resources', $resources);
        $container->setParameter('prestashop.prefix', $prefix);

        foreach ($resources as $resource => $configuration) {
            $definitionId = sprintf('prestashop.repository.%s', $resource);
            $repository = $configuration['repository'];
            $table = $configuration['table'];

            $class = EntityRepository::class;

            if (null !== $repository) {
                $class = $repository;

                if (!class_exists($class)) {
                    throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" does not exist.', $class, $resource));
                }

                if ($class !== EntityRepository::class && !is_subclass_of($class, EntityRepository::class)) {
                    throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" is not an instance of %s.', $class, $resource, EntityRepository::class));
                }
            }

            $definition = new Definition($class, [$table, $prefix, new Reference('doctrine.dbal.prestashop_connection')]);
            $definition->setPublic(true);
            $definition->setLazy(true);

            $container->setDefinition($definitionId, $definition);

        }
    }


}
