<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Model\Category\CategoryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Product\ProductModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Shop\ShopModel;
use Jgrasp\PrestashopMigrationPlugin\Repository\Category\CategoryRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\Product\ProductRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\Shop\ShopRepository;
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
                            ->scalarNode('model')->defaultValue(CategoryModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_category')->end()
                            ->scalarNode('sylius')->defaultValue('taxon')->end()
                        ->end()
                    ->end()
                    ->arrayNode('product')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('product')->end()
                            ->scalarNode('repository')->defaultValue(ProductRepository::class)->end()
                            ->scalarNode('model')->defaultValue(ProductModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_product')->end()
                            ->scalarNode('sylius')->defaultValue('product')->end()
                        ->end()
                    ->end()
                    ->arrayNode('shop')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('shop')->end()
                            ->scalarNode('repository')->defaultValue(ShopRepository::class)->end()
                            ->scalarNode('model')->defaultValue(ShopModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_shop')->end()
                            ->scalarNode('sylius')->defaultValue('channel')->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        }
}
