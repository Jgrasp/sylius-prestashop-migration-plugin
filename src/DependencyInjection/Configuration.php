<?php

namespace Jgrasp\PrestashopMigrationPlugin\DependencyInjection;

use Jgrasp\PrestashopMigrationPlugin\Model\Category\CategoryModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Currency\CurrencyModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Employee\EmployeeModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Lang\LangModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Product\ProductModel;
use Jgrasp\PrestashopMigrationPlugin\Model\Shop\ShopModel;
use Jgrasp\PrestashopMigrationPlugin\Repository\Category\CategoryRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\Currency\CurrencyRepository;
use Jgrasp\PrestashopMigrationPlugin\Repository\EntityRepository;
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
                    ->arrayNode('channel')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('shop')->end()
                            ->scalarNode('repository')->defaultValue(ShopRepository::class)->end()
                            ->scalarNode('model')->defaultValue(ShopModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_shop')->end()
                            ->scalarNode('sylius')->defaultValue('channel')->end()
                            ->scalarNode('priority')->defaultValue(245)->end()
                        ->end()
                    ->end()
                    ->arrayNode('currency')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('currency')->end()
                            ->scalarNode('repository')->defaultValue(CurrencyRepository::class)->end()
                            ->scalarNode('model')->defaultValue(CurrencyModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_currency')->end()
                            ->scalarNode('sylius')->defaultValue('currency')->end()
                            ->scalarNode('priority')->defaultValue(250)->end()
                        ->end()
                    ->end()
                    ->arrayNode('admin_user')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('employee')->end()
                            ->scalarNode('repository')->defaultValue(EntityRepository::class)->end()
                            ->scalarNode('model')->defaultValue(EmployeeModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_employee')->end()
                            ->scalarNode('sylius')->defaultValue('admin_user')->end()
                            ->scalarNode('priority')->defaultValue(100)->end()
                        ->end()
                    ->end()
                    ->arrayNode('locale')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('lang')->end()
                            ->scalarNode('repository')->defaultValue(EntityRepository::class)->end()
                            ->scalarNode('model')->defaultValue(LangModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_lang')->end()
                            ->scalarNode('sylius')->defaultValue('locale')->end()
                            ->scalarNode('priority')->defaultValue(255)->end()
                        ->end()
                    ->end()
                   /* ->arrayNode('product')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('product')->end()
                            ->scalarNode('repository')->defaultValue(ProductRepository::class)->end()
                            ->scalarNode('model')->defaultValue(ProductModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_product')->end()
                            ->scalarNode('sylius')->defaultValue('product')->end()
                            ->scalarNode('priority')->defaultValue(50)->end()
                        ->end()
                    ->end()*/
                    ->arrayNode('taxon')
                        ->addDefaultsIfNotSet()
                        ->children()
                            ->scalarNode('table')->defaultValue('category')->end()
                            ->scalarNode('repository')->defaultValue(EntityRepository::class)->end()
                            ->scalarNode('model')->defaultValue(CategoryModel::class)->end()
                            ->scalarNode('primary_key')->defaultValue('id_category')->end()
                            ->scalarNode('use_translation')->defaultValue(true)->end()
                            ->scalarNode('sylius')->defaultValue('taxon')->end()
                            ->scalarNode('priority')->defaultValue(50)->end()
                        ->end()
                    ->end()
                ->end()
            ->end();
        }
}
