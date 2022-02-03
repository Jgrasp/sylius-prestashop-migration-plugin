<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\EntityTransformer;
use Jgrasp\PrestashopMigrationPlugin\Factory\Factory;
use Jgrasp\PrestashopMigrationPlugin\Mapper\EntityMapper;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;
use ReflectionClass;
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
            $this->createRepositoryDefinition($prefix, $resource, $configuration, $container);
            $this->createFactoryDefinition($resource, $configuration, $container);
            $this->createMapperDefinition($resource, $configuration, $container);
            $this->createDataTransformer($configuration, $container);
        }
    }

    private function createRepositoryDefinition(string $prefix, string $resource, array $configuration, ContainerBuilder $container): void
    {
        $definitionId = $this->getDefinitionId('repository', $resource);

        $repository = $configuration['repository'];
        $table = $configuration['table'];
        $primaryKey = $configuration['primary_key'];

        if (empty($repository)) {
            throw new InvalidConfigurationException(sprintf('You should defined a class for the repository %s.', $resource));
        }

        if (!class_exists($repository)) {
            throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" does not exist.', $repository, $resource));
        }

        if ($repository !== EntityRepository::class && !is_subclass_of($repository, EntityRepository::class)) {
            throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" is not an instance of %s.', $repository, $resource, EntityRepository::class));
        }

        $definition = new Definition($repository, [$table, $prefix, $primaryKey, new Reference('doctrine.dbal.prestashop_connection')]);
        $definition->setPublic(true);
        $definition->setLazy(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createFactoryDefinition(string $resource, array $configuration, ContainerBuilder $container): void
    {
        $definitionId = $this->getDefinitionFactoryId($configuration['sylius']);

        $arguments = [
            new Reference(sprintf('sylius.factory.%s', $configuration['sylius'])),
            new Reference(PropertyAttributeAccessor::class)
        ];

        $definition = new Definition(Factory::class, $arguments);
        $definition->setPublic(true);
        $definition->setLazy(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createMapperDefinition(string $resource, array $configuration, ContainerBuilder $container): void
    {
        $definitionId = $this->getDefinitionMapperId($configuration['sylius']);

        $model = $configuration['model'];
        $reflectionClass = new ReflectionClass($model);

        if (!$reflectionClass->implementsInterface(ModelInterface::class)) {
            throw new InvalidConfigurationException(sprintf('Class %s for the "%s" mapper is not an instance of %s.', $model, $resource, ModelInterface::class));
        }

        $definition = new Definition(EntityMapper::class, [$model, new Reference(PropertyAttributeAccessor::class)]);
        $definition->setPublic(true);
        $definition->setLazy(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createDataTransformer(array $configuration, ContainerBuilder $container): void
    {
        $definitionId = $this->getDefinitionId('data_transformer', $configuration['sylius']);

        $mapperId = $this->getDefinitionMapperId($configuration['sylius']);
        $factoryId = $this->getDefinitionFactoryId($configuration['sylius']);

        $definition = new Definition(EntityTransformer::class, [new Reference($mapperId), new Reference($factoryId)]);
        $definition->setPublic(true);
        $definition->setLazy(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function getDefinitionFactoryId(string $resource): string
    {
        return $this->getDefinitionId('factory', $resource);
    }

    private function getDefinitionMapperId(string $resource): string
    {
        return $this->getDefinitionId('mapper', $resource);
    }

    private function getDefinitionId(string $definition, string $resource): string
    {
        return sprintf('prestashop.%s.%s', $definition, $resource);
    }

}
