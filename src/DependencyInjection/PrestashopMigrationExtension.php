<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Attribute\PropertyAttributeAccessor;
use Jgrasp\PrestashopMigrationPlugin\DataCollector\EntityCollector;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Model\ModelTransformer;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\PrestashopTransformer;
use Jgrasp\PrestashopMigrationPlugin\DataTransformer\Resource\ResourceTransformer;
use Jgrasp\PrestashopMigrationPlugin\Importer\ResourceImporter;
use Jgrasp\PrestashopMigrationPlugin\Model\LocaleFetcher;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelInterface;
use Jgrasp\PrestashopMigrationPlugin\Model\ModelMapper;
use Jgrasp\PrestashopMigrationPlugin\Provider\ResourceProvider;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepositoryInterface;
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
        $publicDirectory = $config['public_directory'];

        if (null === $publicDirectory) {
            throw new InvalidConfigurationException('The configuration for "public_directory" is not defined. Please insert a value (ex : "https://www.example.com/img/p/")');
        }

        $container->setParameter('prestashop.resources', $resources);
        $container->setParameter('prestashop.prefix', $prefix);
        $container->setParameter('prestashop.public_directory', $config['public_directory']);
        $container->setParameter('prestashop.tmp_directory', $config['tmp_directory']);

        foreach ($resources as $resource => $configuration) {
            $this->createRepositoryDefinition($prefix, $resource, $configuration, $container);
            $this->createCollectorDefinition($configuration, $container);
            $this->createMapperDefinition($resource, $configuration, $container);
            $this->createProviderDefinition($configuration, $container);
            $this->createDataTransformer($configuration, $container);
            $this->createResourceImporter($configuration, $container);
        }
    }

    private function createRepositoryDefinition(string $prefix, string $resource, array $configuration, ContainerBuilder $container): void
    {
        $repository = $configuration['repository'];
        $table = $configuration['table'];
        $primaryKey = $configuration['primary_key'];

        $definitionId = $this->getDefinitionRepositoryId($table);

        if (empty($repository)) {
            throw new InvalidConfigurationException(sprintf('You should defined a class for the repository %s.', $resource));
        }

        if (!class_exists($repository)) {
            throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" does not exist.', $repository, $resource));
        }

        if (!is_subclass_of($repository, EntityRepositoryInterface::class)) {
            throw new InvalidConfigurationException(sprintf('Class %s for the repository "%s" is not an instance of %s.', $repository, $resource, EntityRepositoryInterface::class));
        }

        $definition = new Definition($repository, [$table, $prefix, $primaryKey, new Reference('doctrine.dbal.prestashop_connection')]);
        $definition->setPublic(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createCollectorDefinition(array $configuration, ContainerBuilder $container): void
    {
        $table = $configuration['table'];
        $translatable = $configuration['use_translation'] ?? false;

        $definitionId = $this->getDefinitionCollectorId($table);

        $definition = new Definition(EntityCollector::class, [$translatable, new Reference($this->getDefinitionRepositoryId($table))]);
        $definition->setPublic(true);

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

        $definition = new Definition(ModelMapper::class, [$model, new Reference(PropertyAttributeAccessor::class), new Reference(LocaleFetcher::class)]);
        $definition->setPublic(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createProviderDefinition(array $configuration, ContainerBuilder $container): void
    {
        $entity = $configuration['sylius'];

        $definitionId = $this->getDefinitionProviderId($entity);

        $arguments = [
            new Reference(sprintf('sylius.repository.%s', $entity)),
            new Reference(sprintf('sylius.factory.%s', $entity)),
            new Reference(PropertyAttributeAccessor::class),
        ];

        $definition = new Definition(ResourceProvider::class, $arguments);
        $definition->setPublic(true);

        $container->setDefinition($definitionId, $definition);
    }

    private function createDataTransformer(array $configuration, ContainerBuilder $container): void
    {
        $entity = $configuration['sylius'];
        $table = $configuration['table'];

        $modelTransformerId = $this->getDefinitionDataTransformerId($table, 'model');
        $resourceTransformerId = $this->getDefinitionDataTransformerId($entity, 'resource');
        $mapperId = $this->getDefinitionMapperId($entity);
        $providerId = $this->getDefinitionProviderId($entity);

        //MODEL
        $definition = new Definition(ModelTransformer::class, [new Reference($mapperId)]);
        $definition->setPublic(false);

        $container->setDefinition($modelTransformerId, $definition);

        //RESOURCE
        $arguments = [
            new Reference($providerId),
            new Reference(PropertyAttributeAccessor::class),
        ];

        $definition = new Definition(ResourceTransformer::class, $arguments);
        $definition->setPublic(false);

        $container->setDefinition($resourceTransformerId, $definition);

        //PRESTASHOP
        $arguments = [
            new Reference($modelTransformerId),
            new Reference($resourceTransformerId)
        ];

        $definition = new Definition(PrestashopTransformer::class, $arguments);
        $definition->setPublic(true);

        $container->setDefinition($this->getDefinitionDataTransformerId($entity), $definition);
    }

    private function createResourceImporter(array $configuration, ContainerBuilder $container): void
    {
        $entity = $configuration['sylius'];
        $table = $configuration['table'];

        $definitionId = $this->getDefinitionImporterId($entity);

        $arguments = [
            $definitionId,
            new Reference($this->getDefinitionCollectorId($table)),
            new Reference($this->getDefinitionDataTransformerId($entity)),
            new Reference('doctrine.orm.entity_manager')
        ];

        $definition = new Definition(ResourceImporter::class, $arguments);
        $definition
            ->setPublic(false)
            ->addTag('prestashop.importer', ['priority' => $configuration['priority']]);

        $container->setDefinition($definitionId, $definition);
    }

    private function getDefinitionImporterId(string $resource): string
    {
        return $this->getDefinitionId('importer', $resource);
    }

    private function getDefinitionRepositoryId(string $resource): string
    {
        return $this->getDefinitionId('repository', $resource);
    }

    private function getDefinitionCollectorId(string $resource): string
    {
        return $this->getDefinitionId('collector', $resource);
    }

    private function getDefinitionMapperId(string $resource): string
    {
        return $this->getDefinitionId('mapper', $resource);
    }

    private function getDefinitionProviderId(string $resource): string
    {
        return $this->getDefinitionId('provider', $resource);
    }

    private function getDefinitionDataTransformerId(string $resource, string $type = null): string
    {
        $definition = 'data_transformer';

        if (null !== $type) {
            $definition = sprintf($definition.'.%s', $type);
        }

        return $this->getDefinitionId($definition, $resource);
    }

    private function getDefinitionId(string $definition, string $resource): string
    {
        return sprintf('prestashop.%s.%s', $definition, $resource);
    }

}
