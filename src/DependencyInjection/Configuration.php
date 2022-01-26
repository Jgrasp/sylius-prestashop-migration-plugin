<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Repository\CategoryRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\ProductRepository;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('prestashop');

        $rootNode = $treeBuilder->getRootNode()->children();

        $rootNode
            ->scalarNode('connection')->defaultValue("")->info('Doctrine connection name')->cannotBeEmpty()->end()
            ->scalarNode('prefix')->defaultValue('ps_')->info('Table prefix for database')->cannotBeEmpty()->end();

        $this->addResourceSection($rootNode);

        return $treeBuilder;
    }

    public function addResourceSection(NodeBuilder $builder){
        $builder
            ->arrayNode('resources')
                ->children()
                    ->arrayNode('category')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('category')->end()
                            ->scalarNode('repository')->defaultValue(CategoryRepository::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_category')->end()
                        ->end()
                    ->end()
                    ->arrayNode('product')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('product')->end()
                            ->scalarNode('repository')->defaultValue(ProductRepository::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_product')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        }
}
